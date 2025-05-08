<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $payments = Payment::whereHas('property', function ($query) {
            $query->where('user_id', Auth::id());
        })->with(['property', 'tenant'])->get();

        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'tenant_id' => 'required|exists:tenants,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $property = Property::findOrFail($request->property_id);
        $this->authorize('update', $property);

        $payment = Payment::create([
            'property_id' => $property->id,
            'tenant_id' => $request->tenant_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return response()->json($payment->load(['property', 'tenant']), 201);
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        return response()->json($payment->load(['property', 'tenant']));
    }

    public function update(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $validator = Validator::make($request->all(), [
            'amount' => 'sometimes|required|numeric|min:0',
            'due_date' => 'sometimes|required|date',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment->update($request->all());
        return response()->json($payment->load(['property', 'tenant']));
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);
        $payment->delete();
        return response()->json(null, 204);
    }

    public function process(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'payment_details' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Process payment through the property's payment gateway
        $property = $payment->property;
        $paymentSettings = $property->paymentSettings;

        try {
            // This is a placeholder for actual payment processing
            // Implementation will depend on the specific payment gateway
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'payment_method' => $request->payment_method,
                'payment_details' => $request->payment_details,
            ]);

            return response()->json($payment->load(['property', 'tenant']));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 