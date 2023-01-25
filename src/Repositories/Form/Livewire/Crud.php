<?php

namespace Nabre\Quickadmin\Repositories\Form\Livewire;

use Collective\Html\HtmlFacade as Html;
use Nabre\Quickadmin\Repositories\Form\Field;

trait Crud
{

    function rules()
    {
        return collect($this->form()->rules())->mapWithKeys(fn ($r, $k) => ['wireValues.' . $k => $r])->toArray();
    }

    function submit()
    {
        $validatedData = $this->validate();
        $this->form()->save(data_get($validatedData, 'wireValues'));
        $this->modeTable();
    }

    function destroy($id)
    {
        $this->model::find($id)->delete();
        $this->modeTable();
    }
}
