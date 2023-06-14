<?php

namespace Nabre\Quickadmin\Observers;

use App\Models\Contact;
use App\Models\User as Model;
use Carbon\Carbon;

class UserObserver
{
    function creating(Model $model)
    {
        if (is_null($model->password) && is_null($model->email_verified_at)) {
            data_set($model,'email_verified_at',Carbon::now()->format('Y-m-d\TH:i:s'));
        }
    }

    function saved(Model $model)
    {
        if(config('setting.define.autousergenerate')){
            $contact=$model->contact;
            if(is_null($contact)){
                $data=[
                    'email'=>data_get($model,'email'),
                    'lastname'=>data_get($model,'name'),
                    'account'=>data_get($model,'id'),
                ];
                $con=new Contact;
                $con->recursiveSave($data);
            }
        }
    }
}
