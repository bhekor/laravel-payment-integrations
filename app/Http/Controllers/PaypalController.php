<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public function index()
    {
        return view('paypal.index');
    }
    public function initialize(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancelled'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "100.00"
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {

            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }

            return redirect()
                ->route('paypal.index')
                ->with('error', 'Something went wrong.');
        } else {
            return redirect()
                ->route('paypal.index')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
        return view('paypal.checkout');
    }
    public function success(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            // You can do whatever you want with the response here
            // dd($response);

            return redirect()
                ->route('paypal.index')
                ->with('success', 'Transaction complete.');
        } else {
            return redirect()
                ->route('paypal.index')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }
    public function cancelled()
    {
        return redirect()
            ->route('paypal.index')
            ->with('error', $response['message'] ?? 'You have cancelled the transaction.');
    }
}