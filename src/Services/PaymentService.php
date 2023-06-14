<?php

namespace Nabre\Quickadmin\Services;

use Nabre\Quickadmin\Models\Payment;

class PaymentService
{
    var $item;

    function update(array $order)
    {
        $payment_id = data_get($order, 'id');
        $item = Payment::where('payment_id', $payment_id);
        switch ($item->count()) {
            case 0:
                $user = data_get(optional(auth())->user(), 'id');
                $item = new Payment;
                $item = $item->recursiveSave(compact('payment_id', 'user'));
                break;
            default:
                $item = $item->first();
                break;
        }

        $data = [
            'payer_id' => data_get($order, 'payer.payer_id'),
            'payer_email' => data_get($order, 'payer.email_address'),
            'payment_status' => data_get($order, 'status'),
        ];

        $this->item=$item->recursiveSave($data);
        return $this;
    }

    function remove(string $payment_id)
    {
        Payment::where('payment_id', $payment_id)->delete();
        return $this;
    }

    function toPaymentArray(){
        return $this->item->readArray(['payment_id', 'user','payer_id','payer_email','payment_status' ]);
    }
}
