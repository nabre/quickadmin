<?php
namespace Nabre\Quickadmin\Repositories\Form\Supports;

use Nabre\Quickadmin\Repositories\Form\Field;
use Illuminate\Support\Facades\Gate;
use Nabre\Quickadmin\Repositories\Form\CollectionElements;
use Nabre\Quickadmin\Repositories\Form\Item;

trait AddItems{
    public function add($variable, $output = null): Item
    {
        $item = new Item($this, $variable, $output);
        $this->elements->push($item);
        return $item;
    }

    function addPrepend($variable, $output = null)
    {
        $elements = new CollectionElements();
        $item = new Item($this, $variable, $output);
        $this->elements = $elements->push($item)->merge($this->elements);
        return $item;
    }

    public function addSubmit()
    {
        return $this->add(null, Field::SUBMIT);
    }

    public function addBack()
    {
        return $this->addPrepend(null, Field::BACK);
    }

    public function addId()
    {
        $key = $this->getKeyName();
        return $this->add($key, Field::HIDDEN);
    }

    public function addCrud()
    {
        if ($this->crud) {
            $crud = collect([]);
            switch ($this->method) {
                case self::$create:
                    #policy
                    $create = Gate::inspect('create', $this->getData())->allowed();
                    $refresh = (bool) $this->refresh() && Gate::inspect('refresh', $this->getData())->allowed();

                    $crud->put('create', $create);
                    $crud->put('refresh', $refresh);
                    break;
                case self::$update:
                    #policy
                    $view = Gate::inspect('view', $this->getData())->allowed();
                    $update = Gate::inspect('update', $this->getData())->allowed();
                    $copy = Gate::inspect('copy', $this->getData())->allowed();
                    $delete = Gate::inspect('delete', $this->getData())->allowed();
                    $delete_force = $delete && Gate::inspect('delete_force', $this->getData())->allowed();

                    #stato
                    $trashed = (bool) data_get($data = $this->getData(), 'deleted_at') && method_exists($data, 'trashed');
                    $removable = $delete || $delete_force;

                    $crud->put('update', !$trashed && $update);
                    $crud->put('copy', !$trashed && $copy);
                    $crud->put('view', $view);
                    $crud->put('restore', $delete  && $trashed);
                    $crud->put('delete', !$trashed && $delete);
                    $crud->put('delete_force', $delete_force  && $trashed);
                    break;
            }

            $items = null;
            if ((($view ?? null) || ($removable ?? null)) ?? false) {
                $items = $this->get('items');
            }

            $crud = $crud->filter()->keys()->values()->toArray();
            $id = $this->getIdData();
            $trashIcon = $this->trashIcon;
            return $this->add(null, Field::CRUD)->value(compact('id', 'crud', 'items', 'trashIcon'));
        }
        return $this;
    }

    public function addHtml($html)
    {
        return $this->add(null, Field::HTML)->value($html);
    }

    public function addMsg($text, $theme = 'warning')
    {
        return $this->add(null, Field::MSG)->value(get_defined_vars());
    }
}

