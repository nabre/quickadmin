<?php

namespace Nabre\Quickadmin\Repositories\Form2;

use Illuminate\Support\Collection as BaseCollection;
use Nabre\Quickadmin\Repositories\Form\Field;

class CollectionElements extends BaseCollection
{
    function push(...$values)
    {
        foreach ($values as $value) {
            $this->checkNewElement($value);
            $this->items[] = $value;
        }
        return $this;
    }

    protected function checkNewElement(&$element)
    {
        $name = FormConst::VARIABLE;
        $variable = $element->get($name);

        if (isset($variable) && $this->where($name, $variable)->count()) {
            $element->addError('duble variable: ' . $variable);
        }

        if ($element->emptyErrors()) {
            $this->variable($element);
        }

        $element->viewAll();
    }

    function variable(&$element)
    {
        $variable = $element->get(FormConst::VARIABLE);
        $eloquent = $element->getEloquent();

        // $element->view();

        /**
         * Define Type
         */

        $type = 'fake';
        if (isset($variable) && $rel = $eloquent->relationshipFind($variable)) //relation
        {
            $type = 'relation';
            if (in_array($variable, data_get($eloquent, 'required_relation', []))) {
                $element->required();
            }
            $element->set(FormConst::REL, $rel);
        } elseif (isset($variable) &&  in_array($variable, $eloquent->getAttributesArray())) //attribute
        {
            $type = 'attribute';
        } elseif (isset($variable) &&  (in_array($variable, $eloquent->getFillable()) || $variable == $element->getKeyName())) //fillable
        {
            $type = 'fillable';
            $cast = $eloquent->getCasts()[$variable] ?? null;
            $element->set(FormConst::CAST, $cast);
        }

        $element->set(FormConst::TYPE, $type);
        $output = $element->output();

        /**
         * Set required pro & function
         *
         */

        switch ($type) {
            case 'relation':
                $element->addRequiredFn('list');
                break;
            case "fillable":
                break;
            case 'attribute':
                break;
            default:
            case "fake";
                break;;
        }
        /*
        switch ($output) {
            case Field::EMBEDS_MANY:
            case Field::EMBEDS_ONE:
                $element->addRequiredFn('embeds');
                break;
            case Field::CHECKBOX:
            case Field::SELECT;
            case Field::SELECT_MULTI:
            case Field::RADIO:
                $element->addRequiredFn('list');
                break;
        }*/
    }
}
