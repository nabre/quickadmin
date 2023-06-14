<?php

namespace Nabre\Quickadmin\Observers;

use App\Models\Contact as Model;
use App\Models\User;

class ContactObserver
{
    function saved(Model $model)
    {

        if (!is_null($email = data_get($model, 'email'))) {
            if (config('setting.define.autousergenerate')) {
                $account = $model->account;
                if (is_null($account)) {
                    $account = User::where('email', $email)->firstOrCreate();
                    $model->recursiveSaveQuietly(['account' => data_get($account, 'id')]);
                }
            }

            $account = $model->account;
            if (!is_null($account)) {
                data_set($data, 'email', $email);
                data_set($data, 'name', data_get($model, 'full_name'));
                data_set($data, 'permissions', $model->readValue('permission'));
                $account->recursiveSave($data);
            }
        }
    }
}
