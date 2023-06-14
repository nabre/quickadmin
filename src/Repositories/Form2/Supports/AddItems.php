<?php

namespace Nabre\Quickadmin\Repositories\Form2\Supports;

use Nabre\Quickadmin\Repositories\Form2\Field;
use Illuminate\Support\Facades\Gate;
use Nabre\Quickadmin\Repositories\Form2\CollectionElements;
use Nabre\Quickadmin\Repositories\Form2\FormConst;
use Nabre\Quickadmin\Repositories\Form2\Item;

trait AddItems
{
    protected $elements;
    protected $eloquent;
    protected $defCrud = true;

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

    function crudBuildItem($i, $type)
    {
        $crud = collect([]);
        switch ($type) {
            case "L":
                $view = Gate::inspect('view', $i)->allowed() && data_get($this->customCRUD, 'view', $this->defCrud);
                $crud->put('view', $view);
                $delete = Gate::inspect('delete', $i)->allowed() && data_get($this->customCRUD, 'delete', $this->defCrud);
                $trashed = (bool) data_get($data = $i, 'deleted_at') && method_exists($data, 'trashed');
                $crud->put('delete_confirm', !$trashed && $delete  && data_get($this->customCRUD, 'delete_confirm', $this->defCrud));
                $crud->put('delete', !$trashed && $delete);
                switch ($this->write) {
                    case true:
                        break;
                    default:
                        $update = Gate::inspect('update', $i)->allowed() && data_get($this->customCRUD, 'update', $this->defCrud);
                        $copy = Gate::inspect('copy', $i)->allowed()  && data_get($this->customCRUD, 'copy', $this->defCrud);
                        $copyField = $this->elements->where(FormConst::OUTPUT_EDIT, Field::TEXT)->count();
                        #stato
                        $crud->put('edit', !$trashed && $update) && data_get($this->customCRUD, 'edit', $this->defCrud);
                        $crud->put('copy', !$trashed && $copy && $copyField)  && data_get($this->customCRUD, 'copy', $this->defCrud);
                        break;
                }
                break;
            case "F":
                switch ($this->write) {
                    case true:
                        $update = Gate::inspect('update', $i)->allowed()  && data_get($this->customCRUD, 'update', $this->defCrud);
                        $trashed = (bool) data_get($data = $i, 'deleted_at') && method_exists($data, 'trashed');

                        #stato
                        $crud->put('save', !$trashed && $update)  && data_get($this->customCRUD, 'save', $this->defCrud);
                        $delete = Gate::inspect('delete', $i)->allowed() && data_get($this->customCRUD, 'delete', $this->defCrud);
                        $crud->put('delete_confirm', !$trashed && $delete  && data_get($this->customCRUD, 'delete_confirm', $this->defCrud));
                        $crud->put('delete', !$trashed && $delete);
                        break;
                    default:
                        break;
                }
                break;
            case "T":
                $trashed = (bool) data_get($data = $i, 'deleted_at') && method_exists($data, 'trashed');
                $delete = Gate::inspect('delete', $i)->allowed()  && data_get($this->customCRUD, 'delete', $this->defCrud);
                $delete_force = $delete && Gate::inspect('delete_force', $i)->allowed()  && data_get($this->customCRUD, 'delete_force', $this->defCrud);

                $crud->put('restore', $trashed) && data_get($this->customCRUD, 'restore', $this->defCrud);
                $crud->put('delete_confirm', $trashed && $delete_force) && data_get($this->customCRUD, 'delete_confirm', $this->defCrud);
                $crud->put('delete_force', $trashed && $delete_force);
                break;
        }
        return $crud->filter()->keys()->unique()->sort()->toArray();
    }

    function addCrud(string $type)
    {
        $i = $this->eloquent;
        $crud = collect([]);
        switch ($type) {
            case "L":
                $create = Gate::inspect('create', $i)->allowed() && data_get($this->customCRUD, 'create', $this->defCrud);
                $sync = $this->syncCallable() && data_get($this->customCRUD, 'sync', $this->defCrud);
                $crud->put('create', $create);
                $crud->put('sync', $sync);
                break;
            case "F":
                if (strpos($this->MODE, 'L') !== false) {
                    $crud->put('back', true);
                }
                break;
        }
        return $this->add(FormConst::CRUD_VAR_NAME, Field::CRUD)->value($crud->filter()->keys()->sort()->toArray());
    }

    public function addHtml($html)
    {
        return $this->add(null, Field::HTML)->value($html);
    }

    public function addMsg($text, $theme = 'warning')
    {
        return $this->add(null, Field::MSG)->value(get_defined_vars());
    }

    function getEloquent()
    {
        return $this->eloquent;
    }
}
