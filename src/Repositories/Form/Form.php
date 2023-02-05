<?php

namespace Nabre\Quickadmin\Repositories\Form;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Nabre\Quickadmin\Casts\LocalCast;
use Illuminate\Database\Eloquent\Model;
use Nabre\Quickadmin\Repositories\Form\Supports\AddItems;
use Nabre\Quickadmin\Repositories\Form\Supports\VariableSupport;

class Form
{
    use VariableSupport;
    use AddItems;

    var $idData;

    var string $view = '';
    var bool $back = true;
    var bool $crud = true;
    var bool $trashsoft = true;
    var bool $onlyRead = false;

    var $embeds = false;

    var $method;
    var $wirePrefixTop = 'values';
    var $wirePrefix = null;
    var $items = [];

    var $trashIcon;
    var $query;
    var $rows = [];
    var $rules = [];
    var array $trashIds = [];
    var array $activeIds = [];

    protected $elements;
    protected $data;
    protected $eloquent;
    protected $model;

    static function public($idData = null)
    {
        data_set($params, 'idData', data_get((new ManageDB)->id($idData)->array(), 'idData'));
        data_set($params, 'formClass', get_called_class());
        return livewire('manageDatabadeCRUD', $params);
    }

    function submit()
    {
        return function () {
            return true;
        };
    }

    function settings()
    {
    }

    public function build()
    {
    }

    public function query($items)
    {
        return $items;
    }

    function refresh()
    {
    }

    function readSettings($id = null): array
    {
        $defined = (array)$this->settings();
        if (is_null(data_get($defined, 'idData'))) {
            data_set($defined, 'id', $id, false);
        }
        $params = new ManageDB;
        collect($defined)->each(function ($value, $method) use (&$params) {
            $params->$method($value);
        });
        return $params->array();
    }

    function input($data, $reset = false)
    {
        if (!is_null($data)) {
            if (is_string($data)) {
                if (!class_exists($data)) {
                    $data = $this->model::find($data);
                } else {
                    $data = new $data;
                }
            }
            $this->data = $data;
        }

        $this->checkInput($reset);
        return $this;
    }

    public function save(array $request = [])
    {
        $this->data->recursiveSave($request);
        return $this->data;
    }

    private function checkInput($reset = false)
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

        #controllo data
        if (is_null($this->data) || ($this->view == 'list' && $this->rows !== false)) {
            $this->data = new $this->model;
        } elseif (!($this->data instanceof $this->model)) {
            abort(500);
        }

        $this->methodForm();

        #genera struttura

        $this->getElements($reset);
        return $this;
    }


    private function methodForm()
    {
        if (is_null($this->getIdData())) {
            $this->method = self::$create;
        } else {
            $this->method = self::$update;
        }
        return $this;
    }

    function getElements($reset = false)
    {
        $this->trashIcon = method_exists($this->getData(), 'trashed');
        $this->idData = $this->getIdData();

        if ($reset || is_null($this->elements)) {
            if ($this->view == 'form-list') {
                $this->onlyRead = false;
            }

            $this->elements = new CollectionElements();
            $this->build();

            if ($this->view == 'list') {
                $this->addId();
            }

            if ($this->embeds) {
                $key = $this->getEloquent()->getKeyName();
                $this->add($key, Field::HIDDEN)->value($this->getData()->readValue($key));
            }

            /**
             * Elements filter with view param
             */
            $view = (in_array($this->view, ['form', 'form-list'])) ? $this->method   : $this->view;
            $this->elements = $this->elements
                ->filter(function ($e) use ($view) {
                    #filtra items visibili
                    return $e->get(FormConst::VIEW, collect([]))
                        ->filter(function ($v) use ($view) {
                            return $v == $view;
                        })->count();
                })
                ->reject(function ($e) {
                    #rifiuta print static ma nascosti
                    return $e->get(FormConst::OUTPUT) == Field::STATIC && in_array($e->get(FormConst::OUTPUT_EDIT), [Field::HIDDEN]);
                })
                ->map(function ($e) {
                    #imposta valori di default se il parametro non esiste
                    $e->set(FormConst::RULES, ['nullable'], false);

                    #aggiungi errore chiamate funzioni
                    collect([FormConst::REQUIRED_FN, FormConst::REQUIRED_PROPS])->each(function ($r) use (&$e) {
                        $array = $e->get($r, []);
                        if (count($array)) {
                            $errors = $e->get(FormConst::ERRORS);
                            switch ($r) {
                                case FormConst::REQUIRED_FN:
                                    $errors[] = 'No functions have been called: ' . implode(', ', $array);
                                    break;
                                case FormConst::REQUIRED_PROPS:
                                    $errors[] = 'No props were called: ' . implode(', ', $array);
                                    break;
                            }
                            data_set($e, FormConst::ERRORS, $errors);
                        }
                    });
                    return $e;
                })
                ->filter(function ($e) {
                    #rimuovi elemneti con errore in caso di env(production)
                    return !(count($e->get(FormConst::ERRORS, [])) && App::environment('production'));
                })->map(function ($e) {
                    #afggiungi label & wire:model
                    $e->label();
                    $e->wireModel();
                    return $e;
                })
                ->values();
        }

        /**
         * Output
         */

        $this->values();
        $this->valuesHtml();
        $this->rules();

        /**
         * Generate navigation form
         */

        if (!$this->embeds && $this->view == 'form') {
            $submit = $this->elements;
            if ($submit->count() && count($this->rules)) {
                $this->addSubmit();
            } else {
                $this->addMsg('messaggio di errore');
            }
            if ($this->back) {
                $this->addBack();
            }
        }

        $this->items();

        if (in_array($this->view, ['list', 'form-list']) && $this->rows !== false) {
            $this->rowsData();
        }

        return $this;
    }

    private function items()
    {
        $items = $this->elements->reject(function ($e) {
            return data_get($e, FormConst::OUTPUT_EDIT) == Field::HIDDEN;
        })->map(function ($e) {
            $array = collect([]);
            collect([
                FormConst::LABEL,
                FormConst::OUTPUT,
                FormConst::OUTPUT_EDIT,
                FormConst::OPTIONS,
                FormConst::LIST,
                FormConst::EMBED,
                FormConst::EMBED_SORTABLE,
                FormConst::ERRORS,
                FormConst::VALUE,
            ])
                ->filter(fn ($a) => count($a) == 1)
                ->map(fn ($v) => implode('.', (array)$v))
                ->each(function ($key) use (&$array, $e) {
                    switch ($key) {
                        case FormConst::string('ERRORS'):
                        case FormConst::string('EMBED'):
                        case FormConst::string('EMBED_SORTABLE'):
                            $default = false;
                            break;
                        default:
                            $default = null;
                            break;
                    }

                    $add = $e->get($key, $default);
                    if ($key == FormConst::string('VALUE') && $add instanceof Form) {
                        $add = optional($add)->rows();
                    }

                    if ($key == FormConst::string('VALUE') && $e->get(FormConst::OUTPUT) == Field::LABEL) {
                        $add = $e->get(FormConst::LABEL);
                    }

                    $array = $array->put($key, $add);
                });

            if (($errors = data_get($array, FormConst::ERRORS)) !== false) {
                data_set($array, FormConst::VALUE, $errors);
                data_set($array, FormConst::ERRORS, true);
            }

            return $array->reject(fn ($v) => is_null($v))->toArray();
        });

        $this->set('items', $items->values()->toArray());
        return  $this->get('items');
    }

    function values()
    {
        $values = $this->elements->mapWithKeys(function ($e) {
            $key = $e->get(FormConst::VARIABLE);
            if (!is_string($form = $e->get(FormConst::VALUE)) && ($form) instanceof Form) {
                $value = $form->getValues();
            } elseif ($e->get(FormConst::TYPE) == 'fake' || in_array($e->get(FormConst::OUTPUT), [Field::PASSWORD])) {
                $value = $e->get(FormConst::VALUE);
            } else {
                $value = $this->getData()->readValue($key);
            }

            if ($this->method == self::$create) {
                $value = $e->get(FormConst::VALUE_DEFAULT) ??  $value;
            }

            switch ($e->get(FormConst::REL_TYPE)) {
                case "BelongsTo":
                case "HasOne":
                    $value = $value ?? '';
                    break;
                case "BelongsToMany":
                case "HasMany":
                    $value = (array)$value;
                    break;
            }

            return [$key => $value];
        })->toArray();

        $pos = collect([$this->wirePrefixTop, $this->wirePrefix])->filter()->implode('.');
        $this->set($pos, $values);
        return $this->get($this->wirePrefixTop);
    }

    function valuesHtml()
    {
        $values = $this->elements->mapWithKeys(function ($e) {
            $key = $e->get(FormConst::VARIABLE);
            $wire = $e->get(FormConst::OPTIONS_WIREMODEL);
            $value = $this->get($wire);

            if ($items = $e->get(FormConst::LIST_ITEMS, false)) {
                $value = collect($items)->reject(fn ($v, $k) => empty($k))->filter(fn ($v, $k) => in_array($k, (array)$value))->values()->toArray();
            }

            switch ($e->get(FormConst::REL_TYPE)) {
                case "BelongsTo":
                case "HasOne":
                    $value = $value[0] ?? (empty($value) ? null : $value);
                    break;
            }

            switch ($e->get(FormConst::CAST)) {
                case LocalCast::class:
                    $value = $this->getData()->readValue($key, false);
                    break;
            }

            if (is_array($value) && (!count($value))) {
                $value = null;
            }

            if ($e->get(FormConst::OUTPUT) == Field::STATIC) {
                $e->value($value);
            }

            return [$key => $value];
        })->toArray();
        $this->set($this->wirePrefixTop . 'Html', $values);
        return $values;
    }

    function rowsData()
    {
        if ($this->view == 'form-list') {
            $this->{$this->wirePrefixTop} = [];
            $this->rules = [];
        }
        $this->addCrud();
        $this->items();
        $this->rows = $this->set('query', $this->setListQuery()->values())->mapWithKeys(function ($row, $l) {
            return $this->rowItem($row, $l + 1);
        })->toArray();
        return $this;
    }

    function rowItem($row, $l)
    {
        $call = new (get_class($this));
        if ($this->view == 'form-list') {
            $call->view = 'list';
            $call->onlyRead = false;
            $call->crud = false;
            $call->wirePrefix = $l;
        } else {
            $call->view = $this->view;
            $call->onlyRead = $this->onlyRead;
            $call->crud = $this->crud;
            $call->wirePrefix = $this->wirePrefix;
        }

        $call->wirePrefixTop = $this->wirePrefixTop;
        $call->rows = false;
        $call->input($row)->addCrud();
        $id = data_get($row, 'id');

        if ($this->view == 'form-list') {
            $this->{$this->wirePrefixTop} += $call->values();
            $this->rules = array_merge($this->rules, $call->rules());
        }

        return [$id => $call->items()];
    }

    function rules()
    {
        $this->rules = collect([]);

        $ruleAviable = $this->elements->reject(function ($e) {
            return count($e->get(FormConst::ERRORS, []))
                || is_null($e->get(FormConst::VARIABLE))
                || in_array($e->get(FormConst::OUTPUT), [Field::STATIC, Field::MSG, Field::HTML, Field::SUBMIT, Field::BACK]);
        }); //mapWithKeys(fn ($v, $k) => [$this->wirePrefixTop . "." . $k => $v])->toArray();

        $fnEmbeds = function ($e) {
            return in_array($e->get(FormConst::OUTPUT), [Field::EMBEDS_MANY, Field::EMBEDS_ONE]);
        };
        $this->rules = $this->rules->merge(
            $ruleAviable->reject($fnEmbeds)->pluck(FormConst::RULES, FormConst::RULES_NAME)
        );

        $ruleAviable->filter($fnEmbeds)->each(function ($e) {
            $this->rules = $this->rules->merge(
                $e->get(FormConst::VALUE)->rules
            );
        });
        $this->rules = $this->rules->toArray();

        return $this->rules;
    }

    function setListQuery()
    {
        $items = $this->getEloquent();

        if ($this->trashIcon) {
            $this->trashIds = $items->onlyTrashed()->get()->modelKeys();
        }

        if (!count($this->trashIds)) {
            $this->trashsoft = false;
        }

        if ($this->trashIcon && $this->trashsoft) {
            $items = $items->onlyTrashed();
        }

        $items = $items->get();

        $sortField = $this->elements->pluck(FormConst::VARIABLE)->filter()->first();

        $items = $items->sortBy($sortField, SORT_FLAG_CASE)->values();

        if ($this->trashIcon && $this->trashsoft) {
            $items = $items->sortBy(function ($e) {
                return (int) $e->trashed();
            })->values();
        }

        $items = $this->query($items);

        $this->activeIds = $items->modelKeys();

        return $items;
    }

    function getEloquent()
    {
        return $this->eloquent;
    }

    function getModel()
    {
        return $this->model;
    }

    function getData()
    {
        return $this->data;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getValues()
    {
        return $this->{$this->wirePrefixTop};
    }

    function getIdData()
    {
        return data_get($this->data, $this->getKeyName());
    }

    function getKeyName()
    {
        return $this->data->getKeyName();
    }

    function runRefresh()
    {
        $fn = $this->refresh();
        $this->callFn($fn);
        $this->getElements(true);
        return $this;
    }
}
