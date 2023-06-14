<?php

namespace Nabre\Quickadmin\Repositories\Form2\Supports;

use Nabre\Quickadmin\Repositories\Form2\Field;
use Nabre\Quickadmin\Repositories\Form2\Form;
use Nabre\Quickadmin\Repositories\Form2\FormConst;

trait VisibilityItem
{
    protected function view(...$args)
    {
        $this->set(FormConst::VIEW, $args);
        return $this;
    }

    function viewOnlyL()
    {
        $this->view('L');
        return $this;
    }

    function viewOnlyF()
    {
        $this->view('F');
        return $this;
    }

    function viewAll()
    {
        $this->view('F', 'L');
        return $this;
    }

    function editableOnlyCreate()
    {
        switch ($this->builder->getMethod()) {
            case Form::$update:
                $this->set(FormConst::OUTPUT_EDIT, Field::STATIC);
                break;
        }
        return $this;
    }

    function editableOnlyUpdate()
    {
        switch ($this->builder->getMethod()) {
            case Form::$create:
                $this->set(FormConst::OUTPUT_EDIT, Field::STATIC);
                break;
        }
        return $this;
    }
}
