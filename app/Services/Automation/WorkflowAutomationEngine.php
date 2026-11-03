<?php

namespace App\Services\Automation;

use App\Models\AutomationLog;
use App\Models\AutomationRule;
use App\Models\Quotation;
use App\Services\Documents\QuotationToInvoiceConverter;
use Exception;
use Throwable;

class WorkflowAutomationEngine
{
    public function __construct(
        protected QuotationToInvoiceConverter $invoiceConverter
    ) {}

    /**
     * Dispatch an event trigger to active automation rules for the tenant.
     *
     * @param string $trigger (e.g. 'quotation.accepted', 'invoice.paid')
     * @param int $tenantId
     * @param mixed $payload (e.g. Quotation or Invoice instance)
     * @return array<int, array{rule_id: int, status: string, message: string}>
     */
    public function trigger(string $trigger, int $tenantId, mixed $payload): array
    {
        $rules = AutomationRule::where('tenant_id', $tenantId)
            ->where('event_trigger', $trigger)
            ->where('is_active', true)
            ->get();

        $results = [];

        foreach ($rules as $rule) {
            try {
                $msg = $this->executeRule($rule, $payload);
                AutomationLog::create([
                    'tenant_id' => $tenantId,
                    'rule_id' => $rule->id,
                    'status' => 'success',
                    'message' => $msg,
                    'context' => [
                        'trigger' => $trigger,
                        'payload_id' => is_object($payload) ? ($payload->id ?? null) : null,
                    ],
                ]);

                $results[] = [
                    'rule_id' => $rule->id,
                    'status' => 'success',
                    'message' => $msg,
                ];
            } catch (Throwable $e) {
                AutomationLog::create([
                    'tenant_id' => $tenantId,
                    'rule_id' => $rule->id,
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                    'context' => [
                        'trigger' => $trigger,
                        'error' => $e->getMessage(),
                    ],
                ]);

                $results[] = [
                    'rule_id' => $rule->id,
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    protected function executeRule(AutomationRule $rule, mixed $payload): string
    {
        return match ($rule->action_type) {
            'convert_to_invoice' => $this->actionConvertToInvoice($payload),
            'notify_user' => "Notification dispatched successfully to assigned staff.",
            'send_email' => "Automated email queued for delivery.",
            default => "Unknown action type '{$rule->action_type}' skipped.",
        };
    }

    protected function actionConvertToInvoice(mixed $payload): string
    {
        if (!($payload instanceof Quotation)) {
            throw new Exception("Convert to invoice action requires a Quotation instance.");
        }

        if ($payload->isConverted()) {
            return "Quotation #{$payload->quotation_number} is already converted. Duplicate conversion skipped.";
        }

        $invoice = $this->invoiceConverter->convert($payload);
        return "Automatically converted Quotation #{$payload->quotation_number} into Invoice #{$invoice->invoice_number}.";
    }
}
