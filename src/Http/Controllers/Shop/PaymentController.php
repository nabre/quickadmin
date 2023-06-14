<?php

namespace Nabre\Quickadmin\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Services\PaymentService;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    function status(Request $request,$status,$mode=null){
        data_set($param,'payStatus',$status);
        data_set($param,'payMode',$mode);
        data_set($param,'payment',session('payment',[]));
        data_set($param,'getInput',$request->all());

        $CONTENT = livewire('payment',$param);
        return view('nabre-quickadmin::quick.pay', compact('CONTENT'));
    }

    function cart(){
        $CONTENT = 'carrello';
        return view('nabre-quickadmin::quick.pay', compact('CONTENT'));
    }
/*
    function order(PaymentService $service)
    {
        $value = 50;
        $data = [
            'intent' => 'CAPTURE',
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => $this->currency,
                        "value" => $value,
                    ],
                ]
            ],
            "application_context" => [
                "return_url" => route('quickadmin.pay.success'),
                "cancel_url" => route('quickadmin.pay.cancel'),
            ],
        ];

        $order = $this->provider->createOrder($data);
        $service->update($order);
    }

    function checkout_paypal()
    {
        if (($link = collect($order))->keys()->first() == 'error') {
            $CONTENT = 'Errore di autenticazione';
            return view('nabre-quickadmin::quick.user', compact('CONTENT'));
        } else {
            return redirect(data_get(collect(data_get($link, 'links'))->where('rel', 'approve')->first(), 'href'));
        }
    }

    function checkout_card(){
        $CONTENT = 'carta di credito form';
        return view('nabre-quickadmin::quick.user', compact('CONTENT'));
    }

    function success(Request $request, PaymentService $service)
    {
        $order_id = $request::input('token');
        $order = $this->provider->capturePaymentOrder($order_id);

        if (collect($order)->keys()->first() == 'error') {
            $issue = collect(data_get($order, 'error.details.*'))->pluck('issue')->toArray();
            if (in_array("ORDER_ALREADY_CAPTURED", $issue)) {
                $CONTENT = "L'ordine è già stato eseguito.";
            } else {
                $CONTENT = collect($issue)->implode(', ');
            }
            $order = $this->provider->showOrderDetails($order_id);
        } else {
            $service->update($order);
            $CONTENT = 'successo';
        }

        $CONTENT .= '<br>' . collect($order)->toJson();

        return view('nabre-quickadmin::quick.user', compact('CONTENT'));
    }

    function cancel(Request $request, PaymentService $service)
    {
        $order_id = $request::input('token');
        $service->remove($order_id);
        $CONTENT = 'Ordine cancellato.';
        return view('nabre-quickadmin::quick.user', compact('CONTENT'));
    }*/
}
