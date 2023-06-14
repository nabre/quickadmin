<?php

namespace Nabre\Quickadmin\Repositories\Form2;

use Collective\Html\HtmlFacade as Html;
use Collective\Html\FormFacade as Form;
use Egulias\EmailValidator\Result\Reason\CRLFX2;
use Illuminate\Support\Str;
use Nabre\Models\FormFieldType;
use Nabre\Quickadmin\Casts\LocalCast;
use Nabre\Quickadmin\Repositories\LocalizationRepositorie;
use ReflectionClass;

class Field
{
    // Simple fields
    const TEXT = 'text';
    const TEXTAREA = 'textarea';
    const TEXTAREA_CKEDITOR = 'ckeditor';
    const SELECT = 'select';
    const SELECT_MULTI = 'select-multiple';
    //  const CHOICE = 'choice'; //
    const CHECKBOX = 'checkbox';
    const BOOLEAN = 'bool';
    const BOOLEAN2 = 'bool-2';
    const RADIO = 'radio';
    const PASSWORD = 'password';
    const PASSWORD2 = 'password2';
    const HIDDEN = 'hidden';
    //  const FILE = 'file'; //
    const STATIC = 'static';
    //Date time fields
    //   const DATE = 'date';
    //   const DATETIME = 'datetime-local';
    //   const DATETIME_LOCAL = 'datetime-local';
    //    const MONTH = 'month';
    //    const TIME = 'time';
    //    const WEEK = 'week';
    //Special Purpose fields
    // const ADDRESS = 'address';
    const LIVEWIRE = 'livewire';
    //const COLOR = 'color';
    //const SEARCH = 'search'; //
    // const IMAGE = 'image'; //
    const EMAIL = 'email';
    // const URL = 'url'; //
    // const TEL = 'tel'; //
    //  const NUMBER = 'number'; //
    //  const RANGE = 'range'; //
    const TEXT_LANG = 'text-lang';
    //   const ENTITY = 'entity'; //
    //  const FORM = 'form'; //
    //Embeds
    const EMBEDS_MANY = 'embeds-many';
    const EMBEDS_ONE = 'embeds-one';
    //Other
    const MSG = 'message';
    const HTML = 'html';
    const LANG_SELECT = 'lang-select';
    const SUBMIT = 'submit-button';
    const BACK = 'back-button';
    const CRUD = 'crud-interface';
    const LABEL = 'label-head-list';
    const FIELD_TYPE_LIST = 'field-type-list';

    var $elem;
    var $wireModel;

    function options()
    {
        if (!is_null($wire = data_get($this->elem, FormConst::OPTIONS_WIREMODEL))) {
            data_set($this->elem, FormConst::OPTIONS_WIREMODEL, null);
            $this->setOption('wire:model.defer', $wire);
        }
        return (array)data_get($this->elem, FormConst::OPTIONS);
    }

    function id()
    {
        $this->setOption('id', $id = Str::random());
        return  $id;
    }

    function wireAppend(string $str)
    {
        $wire = $this->wireModel . "." . $str;
        data_set($this->elem, FormConst::OPTIONS_WIREMODEL, $wire);
        return $wire;
    }

    function addClass(string $args)
    {
        $classes = array_merge((array)data_get($this->elem, FormConst::OPTIONS_CLASS, []), (array)$args);
        $classes = collect(explode(" ", implode(" ", $classes)))->unique()->sort()->toArray();
        data_set($this->elem, FormConst::OPTIONS_CLASS, $classes);
        return $classes;
    }

    function setOption($name, $value = null)
    {
        $options = data_get($this->elem, FormConst::OPTIONS, []);
        $options[$name] = $value;
        data_set($this->elem, FormConst::OPTIONS, $options);
        return $options;
    }

    function disabled(bool $bool = true)
    {
        $this->setOption(__FUNCTION__, $bool);
        return $bool;
    }

    function multiple(bool $bool = true)
    {
        $this->setOption(__FUNCTION__, $bool);
        return $bool;
    }


    function generate($elem, $ERRORS = false,  bool $write = true, $wire_base = null)
    {
        $this->elem = $elem;
        $wire_val = implode(".", array_filter([$wire_base, data_get($this->elem, FormConst::VARIABLE)]));
        data_set($this->elem, FormConst::OPTIONS_WIREMODEL, $wire_val);
        $this->wireModel = data_get($this->elem, FormConst::OPTIONS_WIREMODEL);
        $output_view = data_get($elem, FormConst::OUTPUT_VIEW);
        $output_edit = data_get($elem, FormConst::OUTPUT_EDIT);
        $list = data_get($elem, FormConst::LIST);
        //$errors = data_get($elem, FormConst::ERRORS_PRINT);
        $value = data_get($elem, FormConst::VALUE);
        $errorHtml = null;

        if ($ERRORS) {
            $msg = $ERRORS->getMessages()[$this->wireModel]?? false;
            if ($msg) {
                $this->addClass('is-invalid');
                $msg = collect((array)$msg)->implode('<br>');
            } else {
                $this->addClass('is-valid');
            }
            $errorHtml = '<div class="invalid-feedback">' . ($msg ?? null) . '</div>
                      <div class="valid-feedback"></div>';
        }

        $output = $write ? $output_edit : $output_view;

        switch ($output) {
            case self::PASSWORD:
                $value = null;
            case self::PASSWORD2:
                $this->addClass("form-control");
                return Form::passwordToggle(null, $value, $this->options()) . $errorHtml;
                break;
            case self::TEXT:
            case self::EMAIL:
                $this->addClass("form-control");
                return Form::input($output, null, null, $this->options()) . $errorHtml;
                break;
            case self::SELECT_MULTI:
                $this->multiple();
            case self::SELECT:
                $this->addClass("form-select");
                //    dd($this,get_defined_vars());
                return Form::select('', $list['items'], '', $this->options()) . $errorHtml;
                break;
                ###
            case self::LANG_SELECT:
                data_set($elem, FormConst::OUTPUT_EDIT, self::SELECT);
                $array = (new LocalizationRepositorie)->select();
                //  dd($array);
                data_set($elem, FormConst::LIST_ITEMS, $array);
                return $this->{__FUNCTION__}($elem);
                break;
            case self::TEXT_LANG:
                $array = (new LocalizationRepositorie)->aviableLang();
                $num = $array->count();
                data_set($elem, FormConst::OUTPUT_EDIT, self::TEXT);

                return $array->map(function ($i) use ($elem, $num) {
                    $wire = data_get($elem, FormConst::OPTIONS_WIREMODEL) . "." . data_get($i, 'lang');
                    data_set($elem, FormConst::OPTIONS_WIREMODEL, $wire);

                    $text = $this->generate($elem, false);

                    if ($num == 1) {
                        return (string) $text;
                    }

                    $icon = Html::tag('span', data_get($i, 'icon'), ['class' => 'input-group-text']);

                    return (string)  Html::div($icon . $text, ['class' => 'input-group mb-1']);
                })->implode('');
                break;
            case self::BOOLEAN:
                $this->setOption('role', 'switch');
                $this->addClass("form-check-input");
                return Html::div(
                    Form::input('checkbox', null, true, $this->options()),
                    ['class' => 'form-check form-switch']
                ) . $errorHtml;
                break;
            case self::BOOLEAN2:
                data_set($elem, FormConst::OUTPUT_EDIT, self::BOOLEAN);
                return $this->{__FUNCTION__}($elem);
                break;
            case self::RADIO:
            case self::CHECKBOX:
                $this->addClass("form-check-input me-1");
                $nCk = Str::random(10);
                $disabled = data_get($list, 'disabled', []);
                $each = collect($list['items'] ?? []);
                $last = $each->keys()->last();
                $listItems = $each->map(function ($v, $k) use ($output, $nCk, $disabled, $errorHtml, $last) {
                    $id = $this->id();
                    $this->disabled(in_array($k, $disabled));
                    if ($last != $k) {
                        $errorHtml = null;
                    }
                    $input = Form::$output(self::RADIO == $output ? $nCk : '', $k, null, $this->options()) . " " . Form::label($id, $v, ['class' => "form-check-label"]) . $errorHtml;
                    $class = "list-group-item ";
                    return (string) Html::tag('li', $input, compact('class'));
                })->implode('');

                return (string) Html::tag('ul', $listItems, ['class' => 'list-group']);
                break;
            case self::TEXTAREA_CKEDITOR:
                $this->addClass('ckeditor');
            case self::TEXTAREA:
                $this->addClass("form-control");
                return Form::textarea(null, null, $this->options()) . $errorHtml;
                break;
            case self::LABEL:
                return $value;
                break;
            case self::STATIC:
                break;
            case self::MSG:
                return (string) Html::div(data_get($value, 'text'), ['class' => 'alert p-1 m-0 alert-' . data_get($value, 'theme')]);
                break;
                ###
            case self::HTML:
                return $value;
                break;
            case self::HIDDEN:
                return Form::input($output, null, null, $this->options());
                break;
            case self::BACK:
                return view('nabre-quickadmin::livewire.form-manage.form.back');
                break;
            case self::SUBMIT:
                return view('nabre-quickadmin::livewire.form-manage.form.submit');
                break;
            case self::CRUD:
                return view('nabre-quickadmin::livewire.form-manage.list.crud', compact('value'));
                break;
            default:
                return '[' . $output . '] non-definito<br>';
                break;
        }
    }
    /*
    static function fieldsListRequired()
    {
        return [self::SELECT, self::SELECT_MULTI, self::CHECKBOX, self::RADIO];
    }

    static function classAdd(&$class, $edit)
    {
        $class = explode(' ', implode(' ', (array)($class ?? null)));
        $edit = explode(' ', implode(' ', (array)($edit ?? null)));
        $class = array_values(array_unique(array_merge($class, $edit)));
    }

    static function name(&$name)
    {
        $name = collect(explode(".", $name))->map(function ($str, $k) {
            if ($k) {
                $str = '[' . $str . ']';
            }
            return $str;
        })->implode('');
    }

    static function fnResolve($data, $array)
    {
        return collect($array)->map(function ($val) use ($data) {
            if (is_array($val)) {
                return self::fnResolve($data, $val);
            } elseif (!is_string($val)  && is_callable($val)) {
                return $val($data);
            } else {
                return $val;
            }
        })->toArray();
    }

    static function generate($it)
    {

        switch ($output) {
            case self::FIELD_TYPE_LIST:
                data_set($it, 'output', self::SELECT);
                //  data_set($it, 'set.list.empty', '-Seleziona-');
                $list = FormFieldType::get()->pluck('string', 'key')->sort();
                $list = $list->reject(function ($v, $k) {
                    return in_array($k, [self::LIVEWIRE, self::MSG, self::EMBEDS_MANY, self::EMBEDS_ONE]);
                })->sort();
                data_set($it, 'set.list.items', $list);
                $html = self::generate($it);
                break;
            case self::LANG_SELECT:
                $list = (new LocalizationRepositorie)->aviableLang()->pluck('language', 'lang')->sort();
                data_set($it, 'output', self::SELECT);
                data_set($it, 'set.list.items', $list);
                $html = self::generate($it);
                break;

            case self::TEXT_LANG:
                $langs = (new LocalizationRepositorie)->aviableLang();
                $numLangs = $langs->count();

                //     self::name($name);
                $langs->sortBy(['position', 'language'])->values()->each(function ($i) use (&$html, $options, $numLangs) {
                    //   $name .= '[' . data_get($i, 'lang') . ']';
                    if (!is_null(data_get($options, 'wire:model'))) {
                        $options['wire:model'] .= "." . data_get($i, 'lang');
                    }
                    //   $value = data_get($value, data_get($i, 'lang'));
                    self::classAdd($options['class'], "form-control");
                    $input = Form::input('text', null, null, $options);
                    if ($numLangs == 1) {
                        $html .= $input;
                    } else {
                        $span = Html::tag('span', data_get($i, 'icon'), ['class' => 'input-group-text', "title" => data_get($i, 'language')]);
                        $html .= Html::div($span . $input, ['class' => 'input-group mb-1']);
                    }
                });
                break;
                ###

            case self::STATIC:

                if (LocalCast::class == data_get($it, 'cast')) {
                    $value = $value['it'] ?? null;
                }

                $html = self::valuePrint($value);

                switch (data_get($it, FormConst::OUTPUT_EDIT)) {
                    case self::BOOLEAN:
                        $html = $html ? '<i class="fa-regular fa-circle-check text-success"></i>' : '<i class="fa-regular fa-circle-xmark text-danger"></i>';
                        break;
                }

                break;
                ###
            case self::EMBEDS_ONE:
            case self::EMBEDS_MANY:
                break;
                ###

        }

        if (!is_null($errors)) {
            $id = data_get($options, 'id', data_get($it, 'variable'));
            $id .= "Feedback";
            if ($errors) {
                $mode = 'invalid';
                $msg = collect($errors)->implode('<br>');
                $html .= '<div id="' . $id . '" class="' . $mode . '-feedback">' . $msg . '</div>';
            }
        }


        return $html;
    }

    static private function valuePrint($value)
    {

        if (is_array($value)) {
            $content = collect($value)->map(function ($value) {
                return (string)Html::div(self::valuePrint($value), ['class' => 'list-group-item p-1']);
            })->implode('');
            $value = empty($content) ? null : Html::div($content, ['class' => 'list-group']);
        }

        return $value;
    }*/

    function getConstants()
    {
        $oClass = new ReflectionClass(__CLASS__);

        return $oClass->getConstants();
    }
}
