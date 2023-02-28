<?php

namespace Nabre\Quickadmin\Console\Commands\Sync;

use Illuminate\Console\Command;
use Nabre\Quickadmin\Models\FormFieldType as Model;
use Nabre\Quickadmin\Repositories\Form\Facades\FieldFacade;

class FormFieldTypeCommand extends Command
{
    protected $signature = 'sync:field-type';
    protected $description = 'Syncronize field types';

    public function handle()
    {
        $types = FieldFacade::getConstants();
        collect($types)->sort()->values()->each(function ($key) {
            $set = Model::where('key', $key)->firstOrCreate();
            $set->recursiveSave(compact('key'));
        });
        Model::whereNotIn('key', $types)->delete();
    }
}
