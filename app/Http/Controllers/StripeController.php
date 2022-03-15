<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function index()
    {
        return view('stripe.index');
    }

    public function initialize(Request $request)
    {
        if (!$request->amount) {
            return redirect()->route('stripe.pay');
        }

        // Enter Your Stripe Secret
        \Stripe\Stripe::setApiKey(config('services.payment.stripe_secret'));

        $amount = $request->amount;
        $amount *= 100;
        $amount = (int) $amount;

        $payment_intent = \Stripe\PaymentIntent::create([
            'description' => 'Stripe Test Payment',
            'amount' => $amount,
            'currency' => 'USD',
            'description' => 'A Test Payment',
            'payment_method_types' => ['card']
        ]);
        $intent = $payment_intent->client_secret;

        return view('stripe.checkout', [
            'intent' => $intent,
            'name' => $request->name,
            'email' => $request->email,
        ]);
    }

    public function callback()
    {
        return view('stripe.success');
    }
}