<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Proposal {{ $proposal->proposal_number }}</title>
    <style>
        body {
            font-family: 'Noto Sans', 'DejaVu Sans', sans-serif;
            color: #1e293b;
            font-size: 13px;
            line-height: 1.6;
            margin: 0;
            padding: 24px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }
        .company-title {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
        }
        .proposal-title {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            margin-top: 10px;
        }
        .section-box {
            margin-bottom: 24px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 16px 20px;
        }
        .section-heading {
            font-size: 15px;
            font-weight: bold;
            color: #1e3a8a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .milestones-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .milestones-table th, .milestones-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        .signatures-box {
            margin-top: 30px;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            padding: 16px;
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="company-title">{{ $tenant->name ?? 'Company Name' }}</div>
            <div class="proposal-title">{{ $proposal->title }}</div>
            <div>Prepared for: <strong>{{ $customer->name ?? 'Valued Client' }}</strong> ({{ $customer->company_name ?? '' }})</div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 16px; font-weight: bold; color: #64748b;">#{{ $proposal->proposal_number }}</div>
            <div>Date: {{ $proposal->issue_date?->format('F d, Y') ?? now()->format('F d, Y') }}</div>
            <div>Valid Until: {{ $proposal->valid_until?->format('F d, Y') ?? 'N/A' }}</div>
            <div style="font-size: 16px; font-weight: bold; color: #166534; margin-top: 8px;">
                Total: {{ $proposal->currency }} {{ number_format($proposal->total_amount, 2) }}
            </div>
        </div>
    </div>

    @foreach($sections as $section)
        <div class="section-box">
            <div class="section-heading">{{ $section->title }}</div>
            <div>{!! nl2br(e($section->content)) !!}</div>
        </div>
    @endforeach

    @if($milestones->isNotEmpty())
        <div class="section-box">
            <div class="section-heading">Payment & Deliverable Milestones</div>
            <table class="milestones-table">
                <thead>
                    <tr style="background-color: #f1f5f9;">
                        <th>Milestone</th>
                        <th>Target Date</th>
                        <th style="text-align: right;">Share</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($milestones as $m)
                        <tr>
                            <td>
                                <strong>{{ $m->title }}</strong>
                                @if($m->description)
                                    <div style="font-size: 11px; color: #64748b;">{{ $m->description }}</div>
                                @endif
                            </td>
                            <td>{{ $m->due_date?->format('M d, Y') ?? 'On Completion' }}</td>
                            <td style="text-align: right;">{{ number_format($m->percentage, 0) }}%</td>
                            <td style="text-align: right;"><strong>{{ $proposal->currency }} {{ number_format($m->amount, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($signatures->isNotEmpty())
        <div class="signatures-box">
            <div style="font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #0f172a;">
                Verified Electronic Signatures & Audit Trail
            </div>
            @foreach($signatures as $sig)
                <div style="margin-bottom: 12px; font-size: 12px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 8px;">
                    <div><strong>Signer:</strong> {{ $sig->signer_name }} ({{ $sig->signer_email }})</div>
                    @if($sig->signer_title)
                        <div><strong>Title:</strong> {{ $sig->signer_title }}</div>
                    @endif
                    <div><strong>Signed At:</strong> {{ $sig->signed_at->format('F d, Y H:i:s T') }}</div>
                    <div><strong>IP Address:</strong> {{ $sig->ip_address }}</div>
                    <div style="font-family: monospace; color: #475569;"><strong>SHA-256 Checksum:</strong> {{ $sig->checksum_sha256 }}</div>
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>

