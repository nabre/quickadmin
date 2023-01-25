<?php

namespace Nabre\Quickadmin\Repositories\Form\Livewire;

use Collective\Html\HtmlFacade as Html;
use Nabre\Quickadmin\Repositories\Form\Field;

trait Table
{
    private function tableGenerate()
    {
        $this->modelKey = (new $this->model)->getKeyName();
        $this->cols=$this->form()->elements->toArray();
        $this->itemsTable = $this->query()->map(function ($data) {
            $item = (new $this->formClass)->input($data)->valuesHtml();
            data_set($item, $this->modelKey, data_get($data, $this->modelKey));
            return $item;
        })->toArray();

        $this->title=$this->model.': elenco';
    }

    private function query()
    {
        return $this->model::get();
    }
}
