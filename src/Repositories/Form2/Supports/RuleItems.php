<?php
namespace Nabre\Quickadmin\Repositories\Form2\Supports;

use Nabre\Quickadmin\Repositories\Form2\FormConst;
use Nabre\Quickadmin\Repositories\Form2\Rule;

trait RuleItems{
    function rules(...$rules){
        $name = FormConst::RULES;
        $rules = collect($rules)->merge($this->get($name, []))->unique()->sort()->values()->toArray();
        $this->set($name, $rules);
        return $this;
    }

    function unique()
    {
        $id='{{idData}}';
        $rule = Rule::unique($this->builder->getModel(), $this->get(FormConst::VARIABLE), $id, $this->builder->getKeyName());
        $this->rules($rule);
        return $this;
    }

    function isRequired(){
        return in_array(Rule::required(),$this->get(FormConst::RULES,[]));
    }
}

