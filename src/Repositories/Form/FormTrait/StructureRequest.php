<?php

namespace Nabre\Quickadmin\Repositories\Form\FormTrait;

use Nabre\Quickadmin\Repositories\Form\FormConst;
use Nabre\Quickadmin\Repositories\Form\Rule;

trait StructureRequest
{
    var $method = null;

    static $create = 'post';
    static $update = 'put';

    function required($method = null)
    {
        $this->rule(Rule::required(), $method);
        return $this;
    }

    function requestCreate($string)
    {
        $this->rule($string, self::$create);
        return $this;
    }

    function requestUpdate($string)
    {
        $this->rule($string, self::$update);
        return $this;
    }

    function rule($string, $method = null)
    {
        $this->checkMethods($method);
        $string = explode("|", implode("|", (array)$string));
        collect($method)->each(function ($method) use ($string) {
            $value = collect((array)  $this->getItemData(FormConst::request($method)))->merge($string)->unique()->filter()->values()->toArray();
            $this->setItemData(FormConst::request($method), $value,true);
        });
        return $this;
    }

    protected function checkMethods(&$method)
    {
        if (is_null($method)) {
            $method = [self::$update, self::$create];
        }
        $method = array_values(array_intersect([self::$update, self::$create], (array) $method));
    }

    private function methodForm()
    {
        if (is_null(data_get($this->data, $this->data->getKeyName()))) {
            $this->method = self::$create;
        } else {
            $this->method = self::$update;
        }

        return $this;
    }

    private function requests()
    {
        return $this->getItemData(FormConst::request($this->method),[]);
    }

}
