<?php

namespace Nabre\Quickadmin\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Nabre\Quickadmin\Http\Controllers\Controller;
use Nabre\Quickadmin\Services\PaymentService;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    function products(){
        $CONTENT = 'prodotti';
        return view('nabre-quickadmin::quick.pay', compact('CONTENT'));
    }

    function cart(){
        $CONTENT = 'carrello';
        return view('nabre-quickadmin::quick.pay', compact('CONTENT'));
    }

    function invoice(){
        $CONTENT = 'fatture';
        return view('nabre-quickadmin::quick.pay', compact('CONTENT'));
    }
}
