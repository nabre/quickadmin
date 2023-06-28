<?php

namespace Nabre\Quickadmin\Repositories\Form2;

use Nabre\Quickadmin\Repositories\Form2\Supports\AddItems;
use Nabre\Quickadmin\Repositories\Form2\Supports\VariableSupport;
use Illuminate\Database\Eloquent\Model;
use Nabre\Quickadmin\Repositories\Form2\Field;

class Form
{
    use VariableSupport;
    use AddItems;

    protected $model;
    protected $data;

    protected $prefix;
    protected $write;
    protected array $arrayFields = [];
    protected $key;
    protected array $customCRUD = [];

    var $paginateItem;

    static function public($mode = null, $idData = null)
    {
        data_set($params, 'pageGetMode', $mode);
        data_set($params, 'idData', $idData);
        data_set($params, 'formClass', get_called_class());
        return livewire('manageData', $params);
    }

    function build()
    {
    }

    function syncCallable()
    {
        return method_exists($this, 'sync');
    }

    function syncCall()
    {
        if ($this->syncCallable()) {
            $this->sync();
        }
    }

    function paginateItem(?int $num = null)
    {
        return $this->paginateItem ?? ($this->paginateItem = $num);
    }

    var $results;
    function callQuery(bool $trash = false, bool $force = false)
    {
        return $trash ?
            (($force ? null : $this->results['trash']) ?? ($this->results['trash'] = ($this->getTrashMode() ? $this->getModel()::onlyTrashed()->get() : false)))
            : (($force ? null : $this->results['list']) ?? ($this->results['list'] = $this->getQuery()));
    }

    function countResults(bool $trash = false)
    {
        return ($query = $this->callQuery($trash, true)) ? $query->count() : false;
    }

    function getQuery()
    {
        return $this->getModel()::get();
    }

    function variablesView($i, $fields, $write = true)
    {
        $array = $this->valuesArray($i, $fields,  $write);

        $this->elements->filter(function ($i) {
            return !is_null(data_get($i, FormConst::REL));
        })->each(function ($i) use (&$array, $write) {
            $variable = data_get($i, FormConst::VARIABLE);
            $sel = data_get($array, $variable);

            $list = collect(data_get($i, FormConst::LIST_ITEMS, []))->filter(fn ($v, $k) => in_array($k, $sel));
            $list = $write ? $list->keys() : $list->values();
            switch (data_get($i, FormConst::REL_TYPE)) {
                case "HasOne":
                case "BelongsTo":
                    $list = $list->first();
                    break;
                case "HasMany":
                case "BelongsToMany":
                    $list = $list->toArray();
                    break;
            }
            data_set($array, $variable, $list);
        });

        return $array;
    }

    function valuesArray($i, $fields, $write)
    {
        $array = $i->readArray($fields, $write);
        if (in_array(FormConst::CRUD_VAR_NAME, $fields)) {
            data_set($array, FormConst::CRUD_VAR_NAME, $this->crudBuildItem($i));
        }
        return $array;
    }

    function buildStructure()
    {
        $this->getEloquent();
        $this->elements = new CollectionElements();
        $this->build();

        $this->elements = $this->elements->map(
            function ($i) {
                $i->set(FormConst::RULES, ['nullable'], false);

                if (in_array(data_get($i, FormConst::OUTPUT_EDIT), [Field::STATIC])) {
                    $rules = null;
                } else {
                    $rules = collect($i->get(FormConst::RULES))->sortBy(function ($v) {
                        return $v == 'required' ? 0 : 1;
                    })->values()->toArray();
                }

                $i->set(FormConst::RULES, $rules);

                collect([FormConst::REQUIRED_FN, FormConst::REQUIRED_PROPS])->each(function ($r) use (&$i) {
                    $array = $i->get($r, []);
                    if (count($array)) {
                        $errors = $i->get(FormConst::ERRORS);
                        switch ($r) {
                            case FormConst::REQUIRED_FN:
                                $errors[] = 'No functions have been called: ' . implode(', ', $array);
                                break;
                            case FormConst::REQUIRED_PROPS:
                                $errors[] = 'No props were called: ' . implode(', ', $array);
                                break;
                        }
                        data_set($i, FormConst::ERRORS, $errors);
                    }
                });

                return $i;
            }
        );

        //CRUD
        $this->addCrud();
        return $this;
    }

    function eloquent()
    {
        #Controllo model
        if (
            is_null($this->model)
            || !class_exists($this->model)
            || !(($eloquent = new $this->model) instanceof Model)
        ) {
            abort(500);
        }

        #controllo eloquent
        if (is_null($this->eloquent)) {
            $this->eloquent = $eloquent;
        } elseif (!($this->eloquent instanceof $this->model)) {
            abort(500);
        }

        return $this->eloquent;
    }

    function getRules()
    {
        return collect([$this->getKeyName() => ['nullable']])->merge(
            collect($this->editSructure()->getElements()->mapWithKeys(function ($i) {
                $key = data_get($i, FormConst::VARIABLE);
                $value = data_get($i, FormConst::RULES);
                return [$key => $value];
            })->toArray())
        )->filter()->toArray();
    }

    function getTrashMode()
    {
        return (bool) count(array_intersect(
            ['Illuminate\Database\Eloquent\SoftDeletes', "Jenssegers\Mongodb\Eloquent\SoftDeletes"],
            class_uses($this->getEloquent())
        ));
    }

    function setCustomCRUD(array $array)
    {
        $this->customCRUD = $array;
        return $this;
    }

    function getData()
    {
        return $this->data;
    }

    function getElements()
    {
        return $this->elements;
    }

    function getKeyName()
    {
        return $this->key ?? ($this->key = (new $this->model)->getKeyName());
    }

    function getModel()
    {
        return $this->model;
    }

    function getIdData()
    {
        return data_get($this->data, $this->getKeyName());
    }

    function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }
}
