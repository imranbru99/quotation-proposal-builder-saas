<?php

namespace App\Services\Documents;

use App\Models\ActivityLog;
use App\Models\DocumentSignature;
use App\Models\Proposal;
use App\Models\Quotation;
use Exception;
use Illuminate\Database\Eloquent\Model;

class ElectronicSignatureService
{
    /**
     * Sign a quotation or proposal electronically with tamper-proof audit trail.
     *
     * @param Model $document
     * @param array{
     *     signer_name: string,
     *     signer_email: string,
     *     signer_title?: string|null,
     *     signature_type?: string,
     *     signature_data: string,
     *     consent_confirmed: bool,
     *     ip_address?: string|null,
     *     user_agent?: string|null
     * } $data
     * @return DocumentSignature
     * @throws Exception
     */
    public function signDocument(Model $document, array $data): DocumentSignature
    {
        if (empty($data['signer_name']) || empty($data['signer_email'])) {
            throw new Exception('Signer name and email are mandatory.');
        }

        if (empty($data['consent_confirmed'])) {
            throw new Exception('Consent confirmation is required to execute an electronic signature.');
        }

        if (empty($data['signature_data'])) {
            throw new Exception('Signature graphical or typed data cannot be empty.');
        }

        // Calculate SHA-256 document checksum
        $payload = json_encode([
            'id' => $document->id,
            'type' => get_class($document),
            'number' => $document->quotation_number ?? $document->proposal_number ?? $document->id,
            'total' => $document->grand_total ?? $document->total_amount ?? 0,
            'signer_email' => strtolower(trim($data['signer_email'])),
            'timestamp' => now()->toIso8601String(),
        ]);
        $checksum = hash('sha256', $payload);

        $ip = $data['ip_address'] ?? request()->ip() ?? '127.0.0.1';
        $userAgent = $data['user_agent'] ?? request()->userAgent() ?? 'Web Browser';

        // 1. Create the signature audit record
        $signature = DocumentSignature::create([
            'tenant_id' => $document->tenant_id,
            'signable_type' => get_class($document),
            'signable_id' => $document->id,
            'signer_name' => trim($data['signer_name']),
            'signer_email' => strtolower(trim($data['signer_email'])),
            'signer_title' => $data['signer_title'] ?? null,
            'signature_type' => $data['signature_type'] ?? 'drawn',
            'signature_data' => $data['signature_data'],
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'checksum_sha256' => $checksum,
            'consent_confirmed' => true,
            'signed_at' => now(),
        ]);

        // 2. Lock the document status
        if ($document instanceof Proposal) {
            $document->update([
                'status' => 'signed',
                'signed_at' => now(),
                'accepted_at' => $document->accepted_at ?? now(),
            ]);
        } elseif ($document instanceof Quotation) {
            $document->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);
        }

        // 3. Record audit trail
        ActivityLog::create([
            'tenant_id' => $document->tenant_id,
            'action' => 'signed',
            'subject_type' => get_class($document),
            'subject_id' => $document->id,
            'description' => "Document electronically signed by {$data['signer_name']} ({$data['signer_email']}) from IP {$ip}. Checksum: " . substr($checksum, 0, 12) . "...",
            'ip_address' => $ip,
        ]);

        return $signature;
    }
}
