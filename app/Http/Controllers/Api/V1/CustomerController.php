<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        $customers = Customer::latest()->paginate(20);
        return response()->json($customers);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'type' => 'nullable|in:company,individual',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'tax_number' => 'nullable|string|max:100',
            'billing_address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string|size:2',
            'currency' => 'nullable|string|size:3',
        ]);

        $validated['tenant_id'] = $request->user()->tenant_id;
        $customer = Customer::create($validated);

        return response()->json([
            'message' => 'Customer created successfully',
            'customer' => $customer,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $customer = Customer::with(['quotations', 'invoices', 'payments'])->findOrFail($id);
        return response()->json($customer);
    }
}

