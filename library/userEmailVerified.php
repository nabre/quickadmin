<?php

use Illuminate\Contracts\Auth\MustVerifyEmail;

function userEmailVerified()
{
    $request = request();
    return !$request->user() ||
        ($request->user() instanceof MustVerifyEmail &&
            !$request->user()->hasVerifiedEmail());
}
