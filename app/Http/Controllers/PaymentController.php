<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payment');
    }

    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount, // Amount in cents
            'currency' => $request->currency,
            'metadata' => [
                'name' => $request->name,
                'email' => $request->email,
            ],
        ]);

        Payment::create([
            'full_name' => $request->name,
            'email' => $request->email,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'payment_intent_id' => $paymentIntent->id,
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);

    }
}
