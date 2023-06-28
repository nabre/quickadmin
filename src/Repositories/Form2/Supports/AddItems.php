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

    function crudBuildItem($i)
    {
        $crud = collect([]);

        #view
        $view = Gate::inspect('view', $i)->allowed() && data_get($this->customCRUD, 'view', $this->defCrud);
        #put
        $update = Gate::inspect('update', $i)->allowed() && data_get($this->customCRUD, 'update', $this->defCrud);
        $trashed = (bool) data_get($data = $i, 'deleted_at') && method_exists($data, 'trashed');
        #copy
        $copy = Gate::inspect('copy', $i)->allowed()  && data_get($this->customCRUD, 'copy', $this->defCrud);
        $copyField = $this->elements->where(FormConst::OUTPUT_EDIT, Field::TEXT)->count();
        #delete
        $delete = Gate::inspect('delete', $i)->allowed() && data_get($this->customCRUD, 'delete', $this->defCrud);
        #delete force
        $delete_force = $delete && Gate::inspect('delete_force', $i)->allowed()  && data_get($this->customCRUD, 'delete_force', $this->defCrud);
        #stato
        $update = Gate::inspect('update', $i)->allowed()  && data_get($this->customCRUD, 'update', $this->defCrud);
        $trashed = (bool) data_get($data = $i, 'deleted_at') && method_exists($data, 'trashed');

        #stato
       // $crud->put('view', !$trashed && $view);
        $crud->put('edit', !$trashed && $update && data_get($this->customCRUD, 'edit', $this->defCrud));
        $crud->put('copy', !$trashed && $copy && $copyField  && data_get($this->customCRUD, 'copy', $this->defCrud));
        $crud->put('delete', !$trashed && $delete);
        $crud->put('restore', $trashed && data_get($this->customCRUD, 'restore', $this->defCrud));
        $crud->put('delete_force', $trashed && $delete_force);

        $crud = $crud->filter()->keys();

        if ($crud->filter(fn ($v) => in_array($v, ['delete', 'delete_force']))->count()) {
            $crud->push('delete_confirm');
        }

        return $crud->unique()->values()->toArray();
    }

    function addCrud()
    {
        $i = $this->eloquent;
        $crud = collect([]);

        $create = Gate::inspect('create', $i)->allowed() && data_get($this->customCRUD, 'create', $this->defCrud);
        $sync = $this->syncCallable() && data_get($this->customCRUD, 'sync', $this->defCrud);
        $crud->put('create', $create);
        $crud->put('sync', $sync);

        return $this->add(FormConst::CRUD_VAR_NAME, Field::CRUD)->value($crud->filter()->keys()->toArray());
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
        return $this->eloquent ?? $this->eloquent();
    }
}
