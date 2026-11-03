@extends('portal.layout')

@section('title', "Sign Proposal: {$proposal->proposal_number}")
@section('header_company', $tenant->name ?? 'Secure Document Signing')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 sm:p-8 bg-slate-900 text-white">
            <div class="text-xs font-semibold uppercase tracking-wider text-blue-400">Electronic Signature Execution</div>
            <h1 class="text-2xl font-bold mt-1">{{ $proposal->title }}</h1>
            <p class="text-xs text-slate-300 mt-1">Proposal #{{ $proposal->proposal_number }} • Total Value: {{ $proposal->currency }} {{ number_format($proposal->total_amount, 2) }}</p>
        </div>

        <form action="{{ route('portal.sign.execute', ['signToken' => $proposal->sign_token]) }}" method="POST" id="signatureForm" class="p-6 sm:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Full Legal Name *</label>
                    <input type="text" name="signer_name" id="signer_name" value="{{ old('signer_name', $customer->name) }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Signer Email *</label>
                    <input type="email" name="signer_email" id="signer_email" value="{{ old('signer_email', $customer->email) }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Signer Title / Designation (Optional)</label>
                    <input type="text" name="signer_title" placeholder="e.g. Managing Director, CEO, Project Lead" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Signature Type Selector & Canvas -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-2">Signature *</label>
                <div class="flex items-center space-x-4 mb-3 text-xs font-medium">
                    <button type="button" id="tabDraw" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white font-semibold">Draw Signature</button>
                    <button type="button" id="tabType" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">Type Signature</button>
                </div>

                <!-- Canvas Pad -->
                <div id="drawContainer" class="relative">
                    <canvas id="sigCanvas" width="600" height="180" class="w-full border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 cursor-crosshair touch-none"></canvas>
                    <div class="absolute bottom-2 right-2">
                        <button type="button" id="clearCanvas" class="text-xs text-rose-600 hover:text-rose-800 font-semibold px-2 py-1 bg-white/90 rounded border border-rose-200 shadow-xs">
                            Clear Signature
                        </button>
                    </div>
                </div>

                <!-- Type Signature Input -->
                <div id="typeContainer" class="hidden">
                    <input type="text" id="typedSignatureInput" placeholder="Type your legal signature here..." class="w-full px-4 py-3 border border-slate-300 rounded-xl text-xl font-serif italic text-blue-900 bg-slate-50">
                </div>

                <!-- Hidden Input holding final signature data -->
                <input type="hidden" name="signature_data" id="signature_data">
            </div>

            <!-- Consent Checkbox -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <label class="flex items-start space-x-3 cursor-pointer">
                    <input type="checkbox" name="consent_confirmed" value="1" required class="mt-1 h-4 w-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                    <span class="text-xs text-slate-600 leading-relaxed">
                        I confirm my legal intention to electronically execute this document. I understand that this electronic signature is legally binding and enforceable, and I agree to the terms, delivery milestones, and payment schedule set forth in Proposal #{{ $proposal->proposal_number }}.
                    </span>
                </label>
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('portal.proposal.view', ['token' => $proposal->view_token]) }}" class="text-xs text-slate-500 hover:underline">
                    ← Return to Proposal Review
                </a>
                <button type="submit" id="submitBtn" class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Confirm & Execute Signature
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('sigCanvas');
        const ctx = canvas.getContext('2d');
        const clearBtn = document.getElementById('clearCanvas');
        const sigDataInput = document.getElementById('signature_data');
        const form = document.getElementById('signatureForm');
        const tabDraw = document.getElementById('tabDraw');
        const tabType = document.getElementById('tabType');
        const drawContainer = document.getElementById('drawContainer');
        const typeContainer = document.getElementById('typeContainer');
        const typedInput = document.getElementById('typedSignatureInput');
        const nameInput = document.getElementById('signer_name');

        let isDrawing = false;
        let mode = 'draw';
        let hasDrawn = false;

        ctx.strokeStyle = '#0f172a';
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return {
                x: (clientX - rect.left) * (canvas.width / rect.width),
                y: (clientY - rect.top) * (canvas.height / rect.height)
            };
        }

        function start(e) {
            isDrawing = true;
            hasDrawn = true;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            e.preventDefault();
        }

        function move(e) {
            if (!isDrawing) return;
            const pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            e.preventDefault();
        }

        function stop() {
            if (isDrawing) {
                isDrawing = false;
            }
        }

        canvas.addEventListener('mousedown', start);
        canvas.addEventListener('mousemove', move);
        canvas.addEventListener('mouseup', stop);
        canvas.addEventListener('mouseleave', stop);

        canvas.addEventListener('touchstart', start, { passive: false });
        canvas.addEventListener('touchmove', move, { passive: false });
        canvas.addEventListener('touchend', stop);

        clearBtn.addEventListener('click', function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasDrawn = false;
            sigDataInput.value = '';
        });

        tabDraw.addEventListener('click', function () {
            mode = 'draw';
            tabDraw.className = 'px-3 py-1.5 rounded-lg bg-blue-600 text-white font-semibold';
            tabType.className = 'px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200';
            drawContainer.classList.remove('hidden');
            typeContainer.classList.add('hidden');
        });

        tabType.addEventListener('click', function () {
            mode = 'type';
            tabType.className = 'px-3 py-1.5 rounded-lg bg-blue-600 text-white font-semibold';
            tabDraw.className = 'px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200';
            drawContainer.classList.add('hidden');
            typeContainer.classList.remove('hidden');
            if (!typedInput.value && nameInput.value) {
                typedInput.value = nameInput.value;
            }
        });

        form.addEventListener('submit', function (e) {
            if (mode === 'draw') {
                if (!hasDrawn) {
                    alert('Please provide a handwritten signature on the canvas or switch to typed signature.');
                    e.preventDefault();
                    return;
                }
                sigDataInput.value = canvas.toDataURL('image/png');
            } else {
                if (!typedInput.value.trim()) {
                    alert('Please type your legal signature.');
                    e.preventDefault();
                    return;
                }
                sigDataInput.value = 'Typed: ' + typedInput.value.trim();
            }
        });
    });
</script>
@endsection
