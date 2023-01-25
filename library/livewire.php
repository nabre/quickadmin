<?php

use Illuminate\Support\Str;

function livewire($fn, array $params = [],?string $id = null)
{
    $id = $id ?? Str::random(40);
    return view('nabre-quickadmin::skeleton.code.livewire', get_defined_vars());
}
