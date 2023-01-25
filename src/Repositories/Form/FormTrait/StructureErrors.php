<?php

namespace Nabre\Quickadmin\Repositories\Form\FormTrait;

use Nabre\Quickadmin\Repositories\Form\Field;
use Nabre\Quickadmin\Repositories\Form\FormConst;
use Nabre\Quickadmin\Repositories\Form\QueryElements;
use Nabre\Quickadmin\Repositories\Form\Rule;

trait StructureErrors
{

    private function checkErrors()
    {
        $this->errors();
        $this->checkSubmitAviable();
    }

    private function errors()
    {
        $mode = strtolower(env('APP_ENV', 'production'));

        switch ($mode) {
            case "local":
                break;
            default:
                $this->elements = (new QueryElements($this->elements))->removeInexistents()->results();
                break;
        }

        $this->elements = $this->elements->map(function ($i) use ($mode) {
            $errors = collect([]);
            $type = data_get($i, 'type', false);

            if (!$type) {
                $errors = $errors->push('Variabile non esistente.');
            } else {

                ##controllo errori
                $output = data_get($i, 'output', false);
                switch ($output) {
                    case Field::EMBEDS_MANY:
                    case Field::EMBEDS_ONE:
                        $string = data_get($i, FormConst::EMBED_FORM, false);
                        if (!$string) {
                            $errors = $errors->push('Il form nidificato non è stato definito.');
                        }

                        break;
                    default:
                        $array = (array) Field::fieldsListRequired();
                        if (in_array($output, $array)) {
                            $list = data_get($i, FormConst::LIST_ITEMS, false);
                            if (!$list) {
                                $errors = $errors->push('Lista items non definita.');
                            }

                            $label = data_get($i, FormConst::LIST_LABEL, false);
                            if (!$label) {
                                $errors = $errors->push('Campo etichetta lista non definito.');
                            }

                            $model = data_get($i, FormConst::REL_MODEL);
                            if (!($this->isAttribute($label, $model) || $this->isFillable($label, $model))) {
                                $errors = $errors->push('Campo etichetta non valido.');
                            }
                        }
                        break;
                }
            }

            if ($errors->count()) {
                switch ($mode) {
                    case "local":
                        break;
                    default:
                        $errors = collect([]);
                        $errors = $errors->push("Configurazione non corretta.");
                        break;
                }
                $this->setData($i, FormConst::ERROR, $errors);
            }
            return $i;
        })->values();
    }

    private function checkSubmitAviable()
    {
        if (!(new QueryElements($this->elements))->removeInexistents()->excludeWithErrors()->results()->count()) {
            $this->submit = false;
            $this->submitError = true;
        }
        return $this;
    }
}
