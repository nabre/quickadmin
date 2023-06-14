<?php

namespace Nabre\Quickadmin\Http\Livewire;

use Livewire\Component;
use Nabre\Quickadmin\Facades\Services\PaymentService;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class Payment extends Component
{
    var $payStatus = null;
    var $payMode = null;
    var array $payment = [];
    var array $getInput = [];
    var float $amount = 10;
    var $currency;
    var $errorMessage;

    var $checkout = [];

    protected $provider;
    protected $order = null;

    var $statusList = [
        'checkout' => [],
        'response' => ['success', 'cancel', 'error'],
    ];

    function mount()
    {
        $this->setPage($this->payStatus,$this->payMode);
    }

    public function render()
    {
        return view('nabre-quickadmin::livewire.payment.index');
    }

    private function order_status()
    {
        $status = data_get($this->order, 'status');
        switch ($status) {
            case 'COMPLETED':
                $this->payment([]);
                $this->order = null;
                break;
        }
        return $status;
    }

    private function work()
    {
        $this->client();
        switch ($this->payStatus) {
            case "checkout":
                if ($this->amount > 0) {
                    $payment_id = data_get($this->payment, 'payment_id');
                    if (is_null($payment_id)) {
                        $data = [
                            'intent' => 'CAPTURE',
                            "purchase_units" => [
                                [
                                    "amount" => [
                                        "currency_code" => $this->currency,
                                        "value" => $this->amount,
                                    ],
                                ]
                            ],
                            "application_context" => [
                                "return_url" => route('quickadmin.pay.response.success'),
                                "cancel_url" => route('quickadmin.pay.response.cancel'),
                            ],
                        ];
                        $this->order = $this->provider->createOrder($data);
                    } else {
                        $this->order = $this->provider->showOrderDetails($payment_id);
                    }
                    $this->order_status();
                    if (!is_null($this->order)) {
                        $this->payment(PaymentService::update($this->order)->toPaymentArray());
                        $this->checkout['paypal'] = data_get(collect(data_get(collect($this->order), 'links'))->where('rel', 'approve')->first(), 'href');
                    }
                } else {
                    #error
                    $message='Non è presente nessun importo di acquisto.';
                    $this->redirectErrorMessage($message);
                }

                break;
            case "response":
                $payment_id = data_get($this->getInput, 'token');
                $this->order = !is_null($payment_id)?$this->provider->showOrderDetails($payment_id):null;
                $status = $this->order_status();
                switch ($this->payMode) {
                    case 'success':
                        if ($status != 'COMPLETED') {
                            $this->order = $this->provider->capturePaymentOrder($payment_id);
                            $payment_id = data_get($this->order, 'payment_id');
                            PaymentService::update($this->order);
                        } else {
                            #error
                            $message='Imprevisto durante il pagamento. Controlla se l\'esito è andato a buon fine.';
                            $this->redirectErrorMessage($message);
                        }
                        break;
                    case 'cancel':
                        $payment_id = data_get($this->getInput, 'token');
                        PaymentService::remove($payment_id);
                        break;
                    case 'error':
                        break;
                }
                $this->payment([]);
                break;
        }
    }

    private function payment($payment)
    {
        $this->payment = $payment;
        request()->session()->put('payment', $this->payment);
        return $this->payment;
    }

    private function client()
    {
        $this->provider = new PayPalClient;
        $this->provider->getAccessToken();
        $this->currency = $this->provider->getCurrency();
    }

    private function redirectErrorMessage($message=null){
        $this->setPage('response','error');
        $this->errorMessage=$message;
    }

    private function setPage($status,$mode=null){
        $status = $this->statusPagePart('payStatus', collect($this->statusList )->keys(),$status);
        $mode = $this->statusPagePart('payMode', collect(collect($this->statusList)->filter(fn ($v, $k) => $k == $status)->first() ?? []),$mode);
        $this->changeUrl(route('quickadmin.pay.status', compact('status', 'mode')));
        $this->work();
    }

    private function statusPagePart(string $var, $list,$value)
    {
        return $this->$var = in_array($value, $list->toArray()) ? $value : (((is_null($this->$var))?null:$this->statusPagePart($var, $list,$this->$var )) ?? $list->first());
    }

    private function changeUrl($url)
    {
        $this->emit('urlChange', $url);
    }
}
