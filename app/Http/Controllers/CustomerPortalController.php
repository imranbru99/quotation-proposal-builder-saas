<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Proposal;
use App\Models\Quotation;
use App\Services\Automation\WorkflowAutomationEngine;
use App\Services\Documents\ElectronicSignatureService;
use App\Services\Documents\QuotationToInvoiceConverter;
use App\Services\Documents\UnicodePdfService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerPortalController extends Controller
{
    public function __construct(
        protected UnicodePdfService $pdfService,
        protected ElectronicSignatureService $signatureService,
        protected QuotationToInvoiceConverter $invoiceConverter,
        protected WorkflowAutomationEngine $automationEngine
    ) {}

    /**
     * View quotation review portal.
     */
    public function viewQuotation(string $token)
    {
        $quotation = Quotation::withoutGlobalScopes()->where('view_token', $token)->firstOrFail();

        // Mark as viewed if first time
        if (empty($quotation->viewed_at) && $quotation->status === 'sent') {
            $quotation->update([
                'status' => 'viewed',
                'viewed_at' => now(),
            ]);
        }

        return view('portal.quotation', [
            'quotation' => $quotation,
            'customer' => $quotation->customer,
            'tenant' => $quotation->tenant,
            'items' => $quotation->items,
        ]);
    }

    /**
     * Accept quotation.
     */
    public function acceptQuotation(Request $request, string $token)
    {
        $quotation = Quotation::withoutGlobalScopes()->where('view_token', $token)->firstOrFail();

        if (in_array($quotation->status, ['accepted', 'converted'], true)) {
            return redirect()->back()->with('info', 'This quotation has already been accepted.');
        }

        $validated = $request->validate([
            'signer_name' => 'required|string|max:255',
            'signer_email' => 'required|email|max:255',
            'consent_confirmed' => 'required|accepted',
            'signature_data' => 'nullable|string',
        ]);

        $signatureData = $validated['signature_data'] ?: 'Typed Signature: ' . $validated['signer_name'];

        $this->signatureService->signDocument($quotation, [
            'signer_name' => $validated['signer_name'],
            'signer_email' => $validated['signer_email'],
            'signature_data' => $signatureData,
            'signature_type' => !empty($validated['signature_data']) ? 'drawn' : 'typed',
            'consent_confirmed' => true,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Trigger workflow automations (e.g. auto-convert to invoice if configured)
        $this->automationEngine->trigger('quotation.accepted', $quotation->tenant_id, $quotation);

        return redirect()->back()->with('success', 'Quotation accepted successfully! Thank you for your business.');
    }

    /**
     * Reject quotation.
     */
    public function rejectQuotation(Request $request, string $token)
    {
        $quotation = Quotation::withoutGlobalScopes()->where('view_token', $token)->firstOrFail();

        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $quotation->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $validated['reason'] ?? 'Declined by customer',
        ]);

        return redirect()->back()->with('info', 'Quotation declined. Our team has been notified.');
    }

    /**
     * Download quotation PDF.
     */
    public function downloadQuotationPdf(string $token): Response
    {
        $quotation = Quotation::withoutGlobalScopes()->where('view_token', $token)->firstOrFail();
        return $this->pdfService->downloadQuotation($quotation);
    }

    /**
     * View proposal.
     */
    public function viewProposal(string $token)
    {
        $proposal = Proposal::withoutGlobalScopes()->where('view_token', $token)->firstOrFail();

        if (empty($proposal->viewed_at) && $proposal->status === 'sent') {
            $proposal->update([
                'status' => 'viewed',
                'viewed_at' => now(),
            ]);
        }

        return view('portal.proposal', [
            'proposal' => $proposal,
            'customer' => $proposal->customer,
            'tenant' => $proposal->tenant,
            'sections' => $proposal->sections,
            'milestones' => $proposal->milestones,
            'signatures' => $proposal->signatures,
        ]);
    }

    /**
     * Sign proposal page.
     */
    public function showSigningPage(string $signToken)
    {
        $proposal = Proposal::withoutGlobalScopes()->where('sign_token', $signToken)->firstOrFail();

        return view('portal.sign', [
            'proposal' => $proposal,
            'customer' => $proposal->customer,
            'tenant' => $proposal->tenant,
            'signatures' => $proposal->signatures,
        ]);
    }

    /**
     * Submit electronic signature for proposal.
     */
    public function executeSignature(Request $request, string $signToken)
    {
        $proposal = Proposal::withoutGlobalScopes()->where('sign_token', $signToken)->firstOrFail();

        if ($proposal->isSigned()) {
            return redirect()->back()->with('info', 'This proposal has already been signed.');
        }

        $validated = $request->validate([
            'signer_name' => 'required|string|max:255',
            'signer_email' => 'required|email|max:255',
            'signer_title' => 'nullable|string|max:255',
            'signature_data' => 'required|string',
            'consent_confirmed' => 'required|accepted',
        ]);

        $this->signatureService->signDocument($proposal, [
            'signer_name' => $validated['signer_name'],
            'signer_email' => $validated['signer_email'],
            'signer_title' => $validated['signer_title'] ?? null,
            'signature_data' => $validated['signature_data'],
            'signature_type' => str_starts_with($validated['signature_data'], 'data:image') ? 'drawn' : 'typed',
            'consent_confirmed' => true,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('portal.proposal.view', ['token' => $proposal->view_token])
            ->with('success', 'Proposal signed successfully! A verified audit record has been generated.');
    }

    /**
     * Download proposal PDF.
     */
    public function downloadProposalPdf(string $token): Response
    {
        $proposal = Proposal::withoutGlobalScopes()->where('view_token', $token)->firstOrFail();
        return $this->pdfService->downloadProposal($proposal);
    }

    /**
     * View invoice portal.
     */
    public function viewInvoice(string $token)
    {
        $invoice = Invoice::withoutGlobalScopes()->where('view_token', $token)->firstOrFail();

        return view('portal.invoice', [
            'invoice' => $invoice,
            'customer' => $invoice->customer,
            'tenant' => $invoice->tenant,
            'items' => $invoice->items,
            'payments' => $invoice->payments,
        ]);
    }

    /**
     * Download invoice PDF.
     */
    public function downloadInvoicePdf(string $token): Response
    {
        $invoice = Invoice::withoutGlobalScopes()->where('view_token', $token)->firstOrFail();
        return $this->pdfService->downloadInvoice($invoice);
    }

    /**
     * View payment receipt.
     */
    public function viewReceipt(string $receiptNumber)
    {
        $payment = Payment::withoutGlobalScopes()->where('receipt_number', $receiptNumber)->firstOrFail();
        return $this->pdfService->downloadReceipt($payment);
    }
}
