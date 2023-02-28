<?php

namespace Nabre\Quickadmin\Forms\User;

<<<<<<< HEAD
=======
use Nabre\Quickadmin\Forms\SettingsBackForm;
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
use Nabre\Quickadmin\Models\Setting as Model;
use Nabre\Quickadmin\Repositories\Form\Field;
use Nabre\Quickadmin\Repositories\Form\Form;

<<<<<<< HEAD
class SettingsForm extends Form
{
    protected $model = Model::class;

    function build()
    {
        $this->add('string');
        $output = function ($data) {
            $name = data_get($data, config('setting.database.key'));
            $data = Model::where(config('setting.database.key'), $name)->doesnthave('user')->first();
            return data_get($data, 'type.key') ?? Field::STATIC;
        };


        $this->add(config('setting.database.value'), $output);
    }

    function query($items)
    {
        $user = auth()->user()->id;
        $keys = Model::doesnthave('user')->get()->where('user_setting', true)->each(function ($i) use ($user) {
            $name = data_get($i, config('setting.database.key'));
            $it = Model::where(config('setting.database.key'), $name)
                ->whereHas('user', function ($i) use ($user) {
                    $key = $i->getModel()->getKeyName();
                    $i->where($key, $user);
                })->get()->first() ?? Model::make();

            if (!data_get($it, $i->getKeyName())) {
                $it->recursiveSave([config('setting.database.key') => $name, 'user' => $user]);
            };
        })->pluck(config('setting.database.key'))->toArray();

        $listUser = Model::whereHas('user', function ($i) use ($user) {
            $key = $i->getModel()->getKeyName();
            $i->where($key, $user);
        });

        (clone $listUser)->whereNotIn(config('setting.database.key'), $keys)->delete();

        return $listUser->get();
    }

    function settings()
    {
        return ['view' => 'form-list', 'head' => false];
    }
=======
class SettingsForm extends SettingsBackForm
{
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
}
