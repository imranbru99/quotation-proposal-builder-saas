<?php

namespace App\Services\Ai;

use ImranDevBd\AiHub\Facades\AIHub;
use Throwable;

class AiAssistantService
{
    /**
     * Generate structured line items for a quotation from freeform requirements.
     *
     * @param string $clientRequirements
     * @param string $businessType
     * @return array<array{name: string, description: string, quantity: float, unit_price: float, unit: string}>
     */
    public function generateQuotationItems(string $clientRequirements, string $businessType = 'Digital Agency'): array
    {
        try {
            $prompt = <<<EOT
You are an expert quotation estimator for a {$businessType}.
Based on the following client requirements, generate 3 to 6 realistic line items with descriptions, estimated quantities, and realistic unit prices in USD.
Output valid JSON array of objects with keys: name, description, quantity, unit_price, unit.

Client Requirements:
"{$clientRequirements}"
EOT;

            $response = AIHub::prompt($prompt)->generate();
            $text = trim((string) $response);

            // Extract JSON if model wrapped in markdown fences
            if (preg_match('/\[.*\]/s', $text, $matches)) {
                $decoded = json_decode($matches[0], true);
                if (is_array($decoded) && !empty($decoded)) {
                    return $decoded;
                }
            }
        } catch (Throwable $e) {
            // Graceful fallback to sensible defaults
        }

        return [
            [
                'name' => 'Professional Discovery & Architecture Planning',
                'description' => 'Requirements gathering, system architecture blueprint, technical specifications, and stakeholder alignment.',
                'quantity' => 1,
                'unit_price' => 1200.00,
                'unit' => 'item',
            ],
            [
                'name' => 'Core Implementation & Development Phase',
                'description' => 'Frontend & backend modular component development, business logic execution, database migrations, and integrations.',
                'quantity' => 40,
                'unit_price' => 75.00,
                'unit' => 'hour',
            ],
            [
                'name' => 'Quality Assurance, Testing & Deployment',
                'description' => 'Automated test suite execution, security auditing, multi-tenant isolation verification, and production release.',
                'quantity' => 1,
                'unit_price' => 850.00,
                'unit' => 'item',
            ],
        ];
    }

    /**
     * Generate content for proposal sections (Executive Summary, Scope of Work, Deliverables, Terms).
     */
    public function generateProposalSection(string $sectionType, string $projectDescription, string $clientName = 'Client'): string
    {
        try {
            $prompt = <<<EOT
Write a professional, compelling, and executive-grade {$sectionType} for a formal business proposal.
Client: {$clientName}
Project Brief: "{$projectDescription}"

Format cleanly with clear headings and bullet points where appropriate.
EOT;

            $response = AIHub::prompt($prompt)->generate();
            $text = trim((string) $response);

            if (!empty($text)) {
                return $text;
            }
        } catch (Throwable $e) {
            // Fallback template
        }

        return match ($sectionType) {
            'executive_summary' => "### Executive Summary for {$clientName}\n\nOur team is pleased to present this comprehensive proposal designed to solve your operational challenges, accelerate business delivery, and ensure scalable growth. Through modern technology and rigorous engineering standards, we provide a complete end-to-end solution tailored precisely to your operational requirements.",
            'scope_of_work' => "### Scope of Work\n\n1. Initial requirements audit, domain modeling, and milestone scheduling.\n2. Modular architecture setup with multi-tenant isolation and security hardening.\n3. Complete integration with business workflows, electronic signatures, and automated invoicing.\n4. Comprehensive testing, staging verification, and deployment.",
            'deliverables' => "### Project Deliverables\n\n- Production-ready software platform deployed to approved hosting infrastructure.\n- Comprehensive API and administrator documentation.\n- Role-based access control and security compliance audit report.\n- 30-day post-launch warranty and priority technical assistance.",
            'terms' => "### Terms & Conditions\n\n- Invoicing will occur according to agreed project milestones.\n- All intellectual property transfers to client upon final payment settlement.\n- Project timeline begins upon execution of this proposal and initial deposit.",
            default => "### Overview\n\nDetailed specifications tailored to {$clientName}'s stated goals.",
        };
    }

    /**
     * Generate polite, professional follow-up email for quotations or invoices.
     */
    public function generateFollowUpEmail(string $documentType, string $documentNumber, string $clientName, float $amount, string $currency = 'USD'): string
    {
        try {
            $prompt = "Write a courteous, professional business follow-up email regarding {$documentType} #{$documentNumber} for client {$clientName}. Total amount: {$currency} {$amount}. Include a clear call to action.";
            $response = AIHub::prompt($prompt)->generate();
            $text = trim((string) $response);
            if (!empty($text)) {
                return $text;
            }
        } catch (Throwable $e) {
            // Fallback
        }

        return "Dear {$clientName},\n\nI hope this email finds you well.\n\nWe wanted to follow up regarding {$documentType} #{$documentNumber} in the amount of {$currency} " . number_format($amount, 2) . ".\n\nPlease let us know if you have any questions or require any adjustments. You can easily review and accept the document directly through your customer portal link.\n\nThank you for your partnership.\n\nWarm regards,\nThe Management Team";
    }
}

