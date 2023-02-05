<?php
function impersonateCheck()
{
    return !is_null(auth()->user()) && request()->session()->has('impersonate');
}
