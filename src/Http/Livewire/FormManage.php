<?php

namespace Nabre\Quickadmin\Http\Livewire;

use Livewire\Component;
use Collective\Html\HtmlFacade as Html;
use Collective\Html\FormFacade as Form;
use Illuminate\Support\Str;
use Nabre\Quickadmin\Repositories\Form\Field;
use Nabre\Quickadmin\Repositories\Form\Livewire\Crud;
use Nabre\Quickadmin\Repositories\Form\Livewire\Put;
use Nabre\Quickadmin\Repositories\Form\Livewire\Table;

class FormManage extends Component
{
    use Crud;
    use Put;
    use Table;

    #input
    var $idData;
    var string $model;
    var string $formClass;
    var bool $modal;

    #page
    var ?string $mode = null;
    var ?string $title = null;
    var string $emptyValue = '---';
    var ?string $method;

    #form
    var array $printForm = [];
    var array $wireValues = [];
    var $selectedAdd = null;

    #table
    var array $cols = [];
    var array $itemsTable = [];
    var ?string $modelKey = null;

    private $form;
    private $embedForm;

    function mount()
    {
        if (is_null($this->idData) && is_null($this->mode) || $this->modal) {
            $this->modeTable();
        } else {
            $this->modePut($this->idData);
        }

    }

    function modePut(?string $idData = null)
    {
        $this->mode = 'put';
        $this->modeModelPut($idData);
    }

    function modeModelPut(?string $idData = null)
    {
        $this->idData = $idData;
        $this->formGenerate($idData);
    }

    function modeTable()
    {
        $this->mode = 'table';
        $this->tableGenerate();
        $this->idData=null;
    }

    public function render()
    {
        return view('nabre-quickadmin::livewire.form-manage.index');
    }

    private function form()
    {
        $this->form = $this->form ?? (new $this->formClass)->input($this->model::find($this->idData) ?? $this->model::make());
        return $this->form;
    }
}
