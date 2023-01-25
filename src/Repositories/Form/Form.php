<?php

namespace Nabre\Quickadmin\Repositories\Form;

use Illuminate\Database\Eloquent\Model;
use Nabre\Quickadmin\Repositories\Form\FormTrait\Output;
use Nabre\Quickadmin\Repositories\Form\FormTrait\Structure;
use Nabre\Quickadmin\Repositories\Form\FormTrait\StructureErrors;
use Nabre\Quickadmin\Repositories\Form\FormTrait\StructureRequest;

class Form
{
    use Structure;
    use StructureErrors;
    use StructureRequest;
    use Output;

    private $model = null;
    private $data = null;
    private $collection;
    private $request;
    private $view = false;
    private $redirect = null;

    private $wire = null;

    static function public($data, $modal = false)
    {
        $model = get_class($data);
        $idData = data_get($data, $data->getKeyName());
        $formClass = get_called_class();
        unset($data);
        return livewire('form', get_defined_vars());
    }

    function __construct($data = null)
    {
        $this->input($data);
        return $this;
    }

    function input($data)
    {
        if (!is_null($data)) {
            if (is_string($data)) {
                $this->model = $data;
            } else {
                $this->data = $data;
            }
            $this->check();
        }
        return $this;
    }

    public function embedMode($wire = null)
    {
        $this->elements = null;
        $this->check();
        $this->add($this->collection->getKeyName())->insert();
        $this->checkErrors();

        $this->wire = $wire;

        $this->elements = $this->elements->map(function ($i) {
            $wire = implode(".", array_filter(['wireValues', $this->wire, data_get($i, FormConst::VARIABLE)]));
            data_set($i, FormConst::OPTIONS_WIREMODEL, $wire);

            return $i;
        });

        $this->back = false;
        $this->submit = false;
        $this->form = false;

        return $this;
    }

    public function values($html = false, $embed = false)
    {
        $this->check();
        $this->valueAssign($html, $embed);

        return $this->elements->pluck(FormConst::VALUE, FormConst::VARIABLE)->toArray();
    }

    public function valuesHtml($embed = false)
    {
        return $this->values(true, $embed);
    }

    public function rules()
    {
        $RULES_PATH = FormConst::request($this->method);
        $this->check();

        $rules = collect([]);
        $elements = (new QueryElements($this->elements))->rulesAviable()->rulesExcludeEmbeds()->results();
        $rules = $rules->merge($elements->pluck($RULES_PATH, FormConst::VARIABLE));

        $elements = (new QueryElements($this->elements))->rulesAviable()->rulesOnlyEmbeds()->results();
        $elements->each(function ($i) use (&$rules, $RULES_PATH) {
            $rulesGeneral = data_get($i, $RULES_PATH);

            $prefix = data_get($i, FormConst::EMBED_VARIABLE);
            $output = data_get($i, FormConst::EMBED_OUTPUT);
            switch ($output) {
                case Field::EMBEDS_MANY:
                    $prefix .= '.*';
                    break;
            }

            $embedForm = data_get($i, FormConst::EMBED_FORM);
            $model = data_get($i, FormConst::REL_MODEL);

            $embedRules = $this->embedObject($embedForm, $model)->rules();

            $add = collect($embedRules)->mapWithKeys(fn ($i, $k) => [$prefix . "." . $k => $i]);

            $add = $add->put(data_get($i, FormConst::EMBED_VARIABLE), Rule::nullable());

            $add = $add->toArray();
            $rules = $rules->merge($add);
        });

        return $rules->sortBy(fn ($r, $k) => $k)->toArray();
    }

    public function save(array $request = [])
    {
        $this->data->recursiveSave($request);
        return $this->data;
    }

    private function check()
    {
        #Controllo model
        if (is_null($this->model)) {
            if ($this->data instanceof Model) {
                $this->model = get_class($this->data);
            } else {
                abort(403);
            }
        }

        #controllo collection
        if (is_null($this->collection)) {
            if (class_exists($this->model) && (new $this->model instanceof Model)) {
                $this->collection = new $this->model;
            } else {
                abort(403);
            }
        }

        #controllo data
        if (is_null($this->data)) {
            if ($this->collection instanceof Model) {
                $this->data = new $this->collection;
            } else {
                abort(403);
            }
        }

        #genera struttura
        $this->structure();

        return $this;
    }

    private function embedObject($embedForm, $data)
    {
        return (new $embedForm($data))->embedMode();
    }

    private function valueAssign($html = false, $embed = false)
    {
        if ($html) {
            $this->elements = $this->elements->reject(fn ($i) => in_array(data_get($i, FormConst::OUTPUT), [Field::HIDDEN]))->values();
        }

        $this->elements = $this->elements->map(function ($i) use ($html, $embed) {
            $type = data_get($i, 'type');
            if ($type && $type != 'fake') {
                $name = data_get($i, FormConst::VARIABLE);
                $value = null;
                $relType = data_get($i, FormConst::REL_TYPE);

                if ($type == 'relation') {
                    if (!is_null($embedForm = data_get($i, FormConst::EMBED_FORM))) {
                        switch ($relType) {
                            case "BelongsToMany";
                            case "HasMany";
                            case "EmbedsMany":
                                $value = $this->data->$name->map(function ($item) use ($embedForm, $html) {
                                    return $this->embedObject($embedForm, $item)->values($html, true);
                                })->toArray();
                                $value = !count($value) ? null : $value;
                                $required = in_array(Rule::required(), data_get($i, FormConst::request($this->method)));
                                $item = ($html || !$required) ? null : data_get($i, FormConst::REL_MODEL);
                                $value = is_null($item) ? $value : ($value ?? [$this->embedObject($embedForm, $item)->values($html, true)]);
                                break;
                            case "HasOne":
                            case "BelongsTo":
                            case "EmbedsOne":
                                $required = in_array(Rule::required(), data_get($i, FormConst::request($this->method)));
                                $item = $this->data->$name ?? ($html || !$required ? null : data_get($i, FormConst::REL_MODEL));
                                $value = is_null($item) ? null : $this->embedObject($embedForm, $item)->values($html, true);
                                break;
                        }
                    }else{
                        $value = $this->data->readValue($name);
                    }

                    if (is_null($value)) {
                        switch ($relType) {
                            case "HasMany":
                            case "BelongsToMany":
                                break;
                            case "HasOne":
                            case "BelongsTo":
                                if (is_null(data_get($i, FormConst::LIST_EMPTY))) {
                                    $value = collect(data_get($i, FormConst::LIST_ITEMS, []))->keys()->first();
                                }
                                $value = $value ?? FormConst::EMPTY_KEY;
                                break;
                        }
                    }
                } else {
                    $value = $this->data->readValue($name);
                }
                $overwrite = !is_null($value);
                $this->setData($i, FormConst::VALUE, $value, $overwrite);
            }

            if ($embed) {
                $this->setData($i, FormConst::VALUE_LABEL, data_get($i, FormConst::LABEL), true);
            }

            if ($html) {
                $this->listValue($i);
            }

            return $i;
        });
    }

    private function listValue(&$i)
    {
        $output = data_get($i, FormConst::OUTPUT);

        if (in_array($output, Field::fieldsListRequired())) {
            $value = data_get($i, FormConst::VALUE);

            $list = collect(data_get($i, FormConst::LIST_ITEMS, []));

            $value = collect((array)$value)->reject(fn ($v) => $v == '')->map(function ($v) use ($list) {
                return data_get($list, $v) ?? null;
            })->unique()->values()->toArray();
            $relType = data_get($i, FormConst::REL_TYPE);
            switch ($relType) {
                case "HasOne":
                case "BelongsTo":
                    $value = collect($value)->first();
                    break;
            }
            data_set($i, FormConst::VALUE, $value);
        }

        $label = data_get($i, FormConst::VALUE_LABEL, false);
        if ($label) {
            $value = data_get($i, FormConst::VALUE);
            $value = (string) view('nabre-quickadmin::livewire.form-manage.item-embed', compact('label', 'value'));
            data_set($i, FormConst::VALUE, $value);
        }
    }
}
