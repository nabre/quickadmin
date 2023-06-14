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
    protected $MODE;

    protected $method;
    protected $prefix;
    protected $write;
    protected array $arrayFields = [];
    protected $key;
    protected array $customCRUD = [];

    function __construct($MODE = null)
    {
        $this->MODE = $MODE;
    }

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

    function F($id = null)
    {
        $this->data = $this->getModel()::findOrNew($id);
        if ($this->write) {
            $this->methodForm();
        }
        $this->editSructure(__FUNCTION__);
        return $this->variablesView($this->data, __FUNCTION__, $this->write);
    }

    function getQuery()
    {
        return $this->getModel()::get();
    }

    private function variablesView($i, $fn, $write = false)
    {
        $array = $this->valuesArray($i, $fn, FALSE);
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

    function L()
    {
        $fn = __FUNCTION__;
        $this->editSructure($fn);

        $query = $this->getQuery();

        return [
            'LE' => $query->mapWithKeys(function ($i) use ($fn) {
                return [data_get($i, $this->getKeyName()) => $this->valuesArray($i, $fn, TRUE)];
            }),
            'LV' => $query->mapWithKeys(function ($i) use ($fn) {
                return [data_get($i, $this->getKeyName()) => $this->variablesView($i, $fn)];
            }),
            'TV' => $this->getTrashMode() ? $this->getModel()::onlyTrashed()->get()->mapWithKeys(function ($i) {
                return [data_get($i, $this->getKeyName()) => $this->variablesView($i, 'T')];
            }) : NULL
        ];
    }

    function valuesArray($i, $type, $write = null)
    {
        $write = $write ?? $this->write;
        $array = $i->readArray($this->arrayFields, $write);
        if (!$this->write) {
            // $array;
        }
        if (in_array(FormConst::CRUD_VAR_NAME, $this->arrayFields)) {
            data_set($array, FormConst::CRUD_VAR_NAME, $this->crudBuildItem($i, $type));
        }
        return $array;
    }

    function editSructure($type = null)
    {
        $this->eloquent();

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
        if (!is_null($type)) {
            $this->addCrud($type);
        }

        $this->arrayFields = collect($this->elements)->pluck('variable')->push($this->getKeyName())->unique()->filter()->sort()->toArray();
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
        )->mapWithKeys(function ($value, $key) {
            return [$this->prefix . "." . $key => $value];
        })->toArray();
    }

    function getTrashMode()
    {
        return (bool) count(array_intersect(['Illuminate\Database\Eloquent\SoftDeletes', "Jenssegers\Mongodb\Eloquent\SoftDeletes"], class_uses($this->getEloquent())));
    }

    function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    function setMode($mode)
    {
        $this->write = ($mode == 'E');
        return $this;
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

    function getMode()
    {
        return $this->write;
    }

    function getElements()
    {
        $mode = substr($this->MODE, 0, 1);
        return $this->elements->filter(function ($i) use ($mode) {
            return in_array($mode, data_get($i, FormConst::VIEW));
        });
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

    private function methodForm()
    {
        if (is_null($this->getIdData())) {
            $this->method = self::$create;
        } else {
            $this->method = self::$update;
        }
        return $this->method;
    }

    function getMethod()
    {
        return $this->method ?? $this->methodForm();
    }
}
