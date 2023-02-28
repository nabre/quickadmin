<?php

namespace Nabre\Quickadmin\Repositories\Form;

use Closure;
use Nabre\Quickadmin\Casts\LocalCast;
use Nabre\Quickadmin\Casts\CkeditorCast;
use Nabre\Quickadmin\Casts\PasswordCast;
use Nabre\Quickadmin\Casts\SettingTypeCast;
use Nabre\Quickadmin\Facades\Repositories\AutoLabel;
use Nabre\Quickadmin\Repositories\Form\Facades\FieldFacade;
use Nabre\Quickadmin\Repositories\Form\Supports\VariableSupport;

class Item
{
    use VariableSupport;
    protected $builder;

    function __construct($builder, $variable, $output = null)
    {
        $this->builder = $builder;

        if (strpos($variable, '.') !== false) {
            abort(403, 'The character dot is not allowed: ' . $variable);
        }

        $this->set(FormConst::VARIABLE, $variable);
        $this->set(FormConst::OUTPUT, $output);
    }

    public function __callGet($method, $args)
    {
        return $this->builder->$method(...$args);
    }

    function addRequiredProp($name)
    {
        $name = implode('.', (array)$name);
        $array = collect(data_get($this, FormConst::REQUIRED_PROPS, []))->push($name)->unique()->sort()->values()->toArray();
        data_set($this, FormConst::REQUIRED_PROPS, $array);
        return $this;
    }

    function addRequiredFn(string $name)
    {
        $array = collect(data_get($this, FormConst::REQUIRED_FN, []))->push($name)->unique()->sort()->values()->toArray();
        data_set($this, FormConst::REQUIRED_FN, $array);
        return $this;
    }

    public function addError($msg)
    {
        $name = FormConst::ERRORS;
        $errors = collect(data_get($this, $name, []))->push($msg)->unique()->values()->toArray();
        $this->set(FormConst::ERRORS, $errors);
        return $this;
    }

    function addRule($rules, bool $create = true, bool $update = true)
    {
        $method = $this->builder->getMethod();
        if (
            self::$create == $method && $create ||
            self::$update == $method && $update
        ) {
            $name = FormConst::RULES;
            $rules = collect((array)$rules)->merge($this->get($name, []))->unique()->sort()->values()->toArray();
            $this->set($name, $rules);
        }

        return $this;
    }

    function onlyList()
    {
        return $this->view(true, false, false);
    }

    function onlyForm()
    {
        return $this->view(false);
    }

    function unique()
    {
        $rule = Rule::unique($this->builder->getModel(), $this->get(FormConst::VARIABLE), $this->builder->getIdData(), $this->builder->getData()->getKeyName());
        $this->addRule($rule);
        return $this;
    }

    function confirm()
    {
        $output = data_get($this, FormConst::OUTPUT);
        $name = data_get($this, FormConst::VARIABLE);
        $nameConf = $name .= '_confirmation';
        $this->required();
        $this->addRule(Rule::confirmed());
        $this->builder->add($nameConf, $output)->addRule(Rule::same($name));

        return $this;
    }

    function wireModel()
    {
        $wire = implode(".", array_filter([$this->builder->wirePrefixTop, $this->builder->wirePrefix, $this->get(FormConst::VARIABLE)]));
        $this->set(FormConst::OPTIONS_WIREMODEL, $wire, false);
        $this->set(FormConst::RULES_NAME, $wire, false);
        return $this->get(FormConst::OPTIONS_WIREMODEL);
    }

    function output_edit($value)
    {
        $this->set(FormConst::OUTPUT_EDIT, $value);
        return $this;
    }

    function view(bool $list = true, bool $post = true, bool $put = true)
    {
        $view = collect(collect(get_defined_vars())->filter(fn ($bool) => $bool)->keys());
        $this->set(FormConst::VIEW, $view);
        return $this;
    }

    function required(bool $create = true, bool $update = true)
    {
        $this->addRule(Rule::required(), $create, $update);
        return $this;
    }

<<<<<<< HEAD
=======
    function isRequired(){
        return in_array(Rule::required(),$this->get(FormConst::RULES,[]));
    }

>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
    function email($str = 'rfc,dns')
    {
        $rule = Rule::email($str);
        $this->addRule($rule);
        return $this;
    }

    function disable(bool|Closure $value)
    {
        if ((bool)$this->callFn($value)) {
            $options = $this->get(FormConst::OPTIONS, []);
            $options = collect($options)->push('disabled')->unique()->sort()->values()->toArray();
            $this->set(FormConst::OPTIONS, $options);
        }
    }

    function listDisabled(array|Closure $disabled)
    {
        $this->set(FormConst::LIST_DISABLED, $disabled);
        return $this;
    }

    function value($value = null)
    {
        $type = $this->get(FormConst::TYPE);
        $output = $this->get(FormConst::OUTPUT);

        $this->removeRequiredFn(__FUNCTION__);
        if (!($type == 'fake' || in_array($output, [Field::EMBEDS_MANY, Field::EMBEDS_ONE, Field::STATIC]))) {
            $this->set(FormConst::VALUE_DEFAULT, $value);
            return $this;
        }

        $this->set(FormConst::VALUE, $value);
        return $this;
    }

    function label(?string $label = null)
    {
        if (is_null($label)) {
            $label = function ($data) {
                $label = $this->get(FormConst::VARIABLE);
                if (class_exists(AutoLabel::class)) {
                    $class = get_class($data);
                    $label = AutoLabel::find($class, $label);
                }
                return $label;
            };
        }

        $this->set(FormConst::LABEL, $label, false);
        return $this;
    }

    function list(string|callable $label, ?callable $query = null, bool $sortDesc = false, ?string $empty = null)
    {
        $this->callFn($label);

        $this->removeRequiredFn(__FUNCTION__);

        $this->listEmpty($empty);

        $model = $this->queryGetModel();
        $items = $this->callFn($query, $model) ?? $model::get();

        $fnSort = 'sortBy' . ($sortDesc ? 'Desc' : null);

        $items = $items->$fnSort($label)->values()->pluck($label, $model->getKeyName());

        if (!is_null($empty)) {
            $items = $items->prepend($empty, '');
        }

        $items = $items->toArray();
        $this->set(FormConst::LIST_ITEMS, $items);
    }

    function embeds(string|Form $form, $sortable = false)
    {
        $this->removeRequiredFn(__FUNCTION__);

        if (!($eloForm = (is_string($form) ? new $form : $form)) instanceof Form) {
            $this->addError('embed Class not instance of ' . Form::class);
            return $this;
        }

        if ($this->get(FormConst::REL_MODEL) != $eloForm->getModel()) {
            $this->addError('embed Class not equalo of a data relations');
            return $this;
        }

        $eloForm->wirePrefix = $this->wireModel();
        $eloForm->embeds = true;
        $eloForm->onlyRead = ($eloForm->onlyRead) ? $eloForm->onlyRead : $this->builder->onlyRead;

        $this->set(FormConst::EMBED_FORM, get_class($eloForm));
        $this->set(FormConst::EMBED_ELOQUENT, $eloForm);
        $this->set(FormConst::EMBED_SORTABLE, $sortable);

        switch ($this->get(FormConst::REL_TYPE)) {
            case "BelongsTo":
            case "HasOne":
            case "EmbedsOne":
                $this->set(FormConst::OUTPUT, Field::EMBEDS_ONE);
                $this->set(FormConst::OUTPUT_EDIT, Field::EMBEDS_ONE);

                $value = $this->embedItem($eloForm->getModel());
                break;
            case "BelongsToMany":
            case "HasMany":
            case "EmbedsMany":
                $this->set(FormConst::OUTPUT, Field::EMBEDS_MANY);
                $this->set(FormConst::OUTPUT_EDIT, Field::EMBEDS_MANY);

                $value = collect([$eloForm->getModel()])->map(function ($data) {
                    return $this->embedItem($data);
                });
                break;
        }

        $this->value(null);
        $this->value($value);
    }

    protected function embedItem($data)
    {
        $form = $this->get(FormConst::EMBED_ELOQUENT);
        return $form->input($data, true);
    }

    protected function listEmpty(&$empty, $relCheck = false)
    {
        if ($empty === true) {
            $empty = FormConst::labelSelect();
        }
        if ($empty === false) {
            $empty = null;
        }

        if ($relCheck) {
            return;
        }

        $type = $this->get(FormConst::REL_TYPE);
        $request = $this->get(FormConst::RULES, []);

        switch ($type) {
            case "HasMany":
            case "BelongsToMany":
                $empty = null;
                break;
            case "BelongsTo":
            case "HasOne":
                if (in_array(Rule::required(), $request)) {
                    $empty = null;
                } else {
                    $empty = true;
                    $this->listEmpty($empty, true);
                }
                break;
            case "EmbedsOne":
            case "EmbedsMany":
                break;
        }

        return $this;
    }

    function queryGetModel()
    {
        $model = $this->get(FormConst::REL_MODEL);
        return new $model;
    }

    public function issetErrors()
    {
        return (bool) count(data_get($this, FormConst::ERRORS, []));
    }

    public function emptyErrors()
    {
        return !$this->issetErrors();
    }

    public function getEloquent()
    {
        return $this->builder->getEloquent();
    }

    public function getData()
    {
        return $this->builder->getData();
    }

    public function getMethod()
    {
        return $this->builder->getMethod();
    }

    function getIdData()
    {
        return $this->builder->getIdData();
    }

    function getKeyName()
    {
        return $this->builder->getKeyName();
    }

    private function removeRequiredProp($name)
    {
        $name = implode('.', (array)$name);

        $array = collect(data_get($this, FormConst::REQUIRED_PROPS, []))->reject(fn ($value) => $value == $name)->unique()->sort()->values()->toArray();
        data_set($this, FormConst::REQUIRED_PROPS, $array);

        return $this;
    }

    private function removeRequiredFn($name)
    {
        $name = implode('.', (array)$name);

        $array = collect(data_get($this, FormConst::REQUIRED_FN, []))->reject(fn ($value) => $value == $name)->unique()->sort()->values()->toArray();
        data_set($this, FormConst::REQUIRED_FN, $array);

        return $this;
    }

    function output()
    {
        $output = $this->get(FormConst::OUTPUT);

        $enabled = collect([]);

        switch ($this->get(FormConst::TYPE)) {
            case "fake";
                if (is_null($this->get(FormConst::VARIABLE))) {
                    $enabled = $enabled->push(Field::SUBMIT);
                    $enabled = $enabled->push(Field::BACK);
                    $enabled = $enabled->push(Field::CRUD);
                    $enabled = $enabled->push(Field::HTML);
                    $enabled = $enabled->push(Field::MSG);
                } else {
                    $enabled = $enabled->push(Field::STATIC);
                    $enabled = $enabled->push(Field::HIDDEN);
                    $enabled = $enabled->push(Field::BOOLEAN);
                    $enabled = $enabled->push(Field::TEXT);
                    $enabled = $enabled->push(Field::PASSWORD);
                }
                break;
            case "attribute";
                $enabled = $enabled->push(Field::STATIC);
                $enabled = $enabled->push(Field::BOOLEAN);
                break;
            case "relation":
                switch ($this->get(FormConst::REL_TYPE)) {
                    case "BelongsTo":
                    case "HasOne":
                        $enabled = $enabled->merge([Field::SELECT, Field::RADIO, Field::EMBEDS_ONE]);
                        break;
                    case "BelongsToMany":
                    case "HasMany":
                        $enabled = $enabled->merge([Field::CHECKBOX, Field::SELECT_MULTI, Field::EMBEDS_MANY]);
                        break;
                    case "EmbedsMany":
                        $enabled = $enabled->push(Field::EMBEDS_MANY);
                        break;
                    case "EmbedsOne":
                        $enabled = $enabled->push(Field::EMBEDS_ONE);
                        break;
                }
                break;
            case "fillable";
                switch ($this->get(FormConst::CAST)) {
                    case PasswordCast::class:
                        $enabled = $enabled->push(Field::PASSWORD);
                        break;
                    case LocalCast::class:
                        $enabled = $enabled->push(Field::TEXT_LANG);
                        break;
                    case SettingTypeCast::class:
                        $enabled = $enabled->push(Field::FIELD_TYPE_LIST);
                        break;
                    case "boolean":
                    case "bool":
                        $enabled = $enabled->push(Field::BOOLEAN);
                        break;
                    case CkeditorCast::class:
                        $enabled = $enabled->push(Field::TEXTAREA_CKEDITOR);
                        break;
                    default:
<<<<<<< HEAD
                        $enabled = $enabled->merge(FieldFacade::getConstants());
=======
                        $enabled = $enabled->merge(FieldFacade::getConstants())->values();
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
                        break;
                }
                break;
        }

        $enabled = $enabled->push(Field::STATIC)->push(Field::HIDDEN)->unique()->values();

        if (!$enabled->filter(fn ($str) => $str == $output)->count() && $enabled->count()) {
            $output = $enabled->first();
        }

        $this->set(FormConst::OUTPUT, $output ?? Field::STATIC);
        $output = $this->get(FormConst::OUTPUT);
        $this->set(FormConst::OUTPUT_EDIT, $output);

        if ($output != Field::CRUD) {
            if (in_array($this->builder->view, ['list', 'form-list']) && $this->builder->rows !== false) {
                $this->set(FormConst::OUTPUT, Field::LABEL);
                //  $this->value('prova');
            } elseif ($this->builder->onlyRead) {
                $this->set(FormConst::OUTPUT, Field::STATIC);
            }
        }

        return $output;
    }
}

/**
 *    $this->setItemData(FormConst::EMBED_MODEL, $this->model, true);
 *    $this->setItemData(FormConst::EMBED_DATAKEY, data_get($this->data, $this->data->getKeyName()), true);
 *    $this->setItemData(FormConst::EMBED_VARIABLE, $this->getItemData('variable'), true);
 *    $this->setItemData(FormConst::EMBED_OUTPUT, $this->getItemData('output'), true);
 *    $this->setItemData(FormConst::EMBED_WIRE_MODEL, $this->getItemData('set.rel.model'), true);
 *    $this->setItemData(FormConst::EMBED_OWNERKEY, $this->getItemData('set.rel.ownerKey'), true);
 *
 */
