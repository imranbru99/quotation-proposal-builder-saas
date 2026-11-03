<?php

namespace App\Services\Documents;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Proposal;
use App\Models\Quotation;
use ImranDev\UnicodePdf\UnicodePdfDocument;
use Symfony\Component\HttpFoundation\Response;

class UnicodePdfService
{
    /**
     * Build the PDF document instance for a quotation.
     */
    public function quotationDocument(Quotation $quotation): UnicodePdfDocument
    {
        $tenant = $quotation->tenant;
        $customer = $quotation->customer;
        $items = $quotation->items;

        return unicode_pdf()
            ->loadView('pdf.quotation', compact('quotation', 'tenant', 'customer', 'items'))
            ->title("Quotation-{$quotation->quotation_number}")
            ->author($tenant?->name ?? 'Quotation SaaS')
            ->a4();
    }

    /**
     * Download or stream quotation PDF.
     */
    public function downloadQuotation(Quotation $quotation): Response
    {
        $filename = "quotation-{$quotation->quotation_number}.pdf";
        return $this->quotationDocument($quotation)->download($filename);
    }

    public function streamQuotation(Quotation $quotation): Response
    {
        $filename = "quotation-{$quotation->quotation_number}.pdf";
        return $this->quotationDocument($quotation)->stream($filename);
    }

    /**
     * Build the PDF document instance for an invoice.
     */
    public function invoiceDocument(Invoice $invoice): UnicodePdfDocument
    {
        $tenant = $invoice->tenant;
        $customer = $invoice->customer;
        $items = $invoice->items;
        $payments = $invoice->payments;

        return unicode_pdf()
            ->loadView('pdf.invoice', compact('invoice', 'tenant', 'customer', 'items', 'payments'))
            ->title("Invoice-{$invoice->invoice_number}")
            ->author($tenant?->name ?? 'Quotation SaaS')
            ->a4();
    }

    public function downloadInvoice(Invoice $invoice): Response
    {
        $filename = "invoice-{$invoice->invoice_number}.pdf";
        return $this->invoiceDocument($invoice)->download($filename);
    }

    public function streamInvoice(Invoice $invoice): Response
    {
        $filename = "invoice-{$invoice->invoice_number}.pdf";
        return $this->invoiceDocument($invoice)->stream($filename);
    }

    /**
     * Build the PDF document instance for a proposal.
     */
    public function proposalDocument(Proposal $proposal): UnicodePdfDocument
    {
        $tenant = $proposal->tenant;
        $customer = $proposal->customer;
        $sections = $proposal->sections;
        $milestones = $proposal->milestones;
        $signatures = $proposal->signatures;

        return unicode_pdf()
            ->loadView('pdf.proposal', compact('proposal', 'tenant', 'customer', 'sections', 'milestones', 'signatures'))
            ->title("Proposal-{$proposal->proposal_number}")
            ->author($tenant?->name ?? 'Quotation SaaS')
            ->a4();
    }

    public function downloadProposal(Proposal $proposal): Response
    {
        $filename = "proposal-{$proposal->proposal_number}.pdf";
        return $this->proposalDocument($proposal)->download($filename);
    }

    public function streamProposal(Proposal $proposal): Response
    {
        $filename = "proposal-{$proposal->proposal_number}.pdf";
        return $this->proposalDocument($proposal)->stream($filename);
    }

    /**
     * Build the PDF document instance for a payment receipt.
     */
    public function receiptDocument(Payment $payment): UnicodePdfDocument
    {
        $tenant = $payment->tenant;
        $customer = $payment->customer;
        $invoice = $payment->invoice;

        return unicode_pdf()
            ->loadView('pdf.receipt', compact('payment', 'tenant', 'customer', 'invoice'))
            ->title("Receipt-{$payment->receipt_number}")
            ->author($tenant?->name ?? 'Quotation SaaS')
            ->a4();
    }

    public function downloadReceipt(Payment $payment): Response
    {
        $filename = "receipt-{$payment->receipt_number}.pdf";
        return $this->receiptDocument($payment)->download($filename);
    }
}

