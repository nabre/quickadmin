<?php

namespace Nabre\Quickadmin\Repositories\Form;

use Illuminate\Database\Eloquent\Model;

class ManageDB
{
    var ?string $idData;
    var string $view = 'list';
    var bool $back = true;
    var bool $crud = true;
    var bool $trashsoft = false;
    var bool $onlyRead = false;
    var bool $head = true;
    /**
     * 'idData', 'view', 'back', 'crud', 'trashsoft'
     */

    public function __call($method, $args)
    {
        $enabledFn = array_keys(get_class_vars(get_class($this)));

        if (in_array($method, $enabledFn)) {
            $this->$method =  $args[0];
        }
        return $this;
    }

    function id($id)
    {
        if (!is_string($id) && $id instanceof Model) {
            $key = $id->getKeyName();
            $id = $id->$key;
        }

        $this->idData($id);
        return $this;
    }

    function array()
    {
        $viewEnabled = collect(['list', 'form', 'form-list']);
        if (!in_array($this->view, $viewEnabled->toArray())) {
            $this->view = $viewEnabled->first();
        }

        if(!is_null($this->idData)){
            $this->view('form');
        }

        if (!$this->back) {
            $this->view('form');
        }

        if (!$this->crud) {
            $this->view('list');
        }

        if ($this->view == 'list') {
            $this->onlyRead(true);
        }

        if ($this->view == 'form') {
            $this->onlyRead(false);
        }

        if ($this->view == 'form-list') {
            $this->onlyRead(false);
            $this->crud(false);
        }

        return collect(get_object_vars($this))->reject(fn ($v) => is_null($v))->toArray();
    }
}
