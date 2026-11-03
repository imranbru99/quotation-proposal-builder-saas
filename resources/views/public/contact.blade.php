@extends('public.layout')

@section('title', 'Contact Enterprise Solutions - Quotation SaaS')
@section('meta_description', 'Get in touch with our engineering and enterprise solutions team.')

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Get In Touch</span>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight mt-2">Contact Our Solutions Team</h1>
            <p class="text-sm text-slate-600 mt-2">Have questions about multi-tenancy deployment, custom modules, or API integrations? We are here to help.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
            <form action="{{ route('public.contact.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Your Name *</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Work Email *</label>
                        <input type="email" name="email" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Company / Organization (Optional)</label>
                    <input type="text" name="company" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Message / Requirements *</label>
                    <textarea name="message" rows="4" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="Tell us about your team size, expected quotation volume, and required modules..."></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md transition">
                    Send Inquiry
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
                Direct Contact: <a href="mailto:me@imrandev.bd" class="text-blue-600 font-bold hover:underline">me@imrandev.bd</a> | Developer: <strong>Imran Ahmed</strong> | <a href="https://imrandev.bd" target="_blank" class="hover:underline">https://imrandev.bd</a>
            </div>
        </div>
    </div>
</div>
@endsection
