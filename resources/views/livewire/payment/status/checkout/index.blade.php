<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                {{ $amount }} {{ $currency }}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
              Pay with Creditcard
            </div>
          </div>
    </div>
    <div class="col">
        <div class="card">
                <a class="card-body" href="{{ data_get($checkout,'paypal') }}"> Pay with PayPal </a>
          </div>
    </div>
</div>
