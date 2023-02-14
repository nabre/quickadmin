<?php

namespace Nabre\Quickadmin\View\Components;

use Illuminate\View\Component;
use Nabre\Quickadmin\Repositories\Form\Field;
use Nabre\Quickadmin\Repositories\Form\Facades\FieldFacade;
use Nabre\Quickadmin\Repositories\Form\FormConst;

class Boolean extends Component
{
    var $bool;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        data_set($i, FormConst::VALUE, (bool)$data);
        data_set($i, FormConst::OUTPUT, Field::STATIC);
        data_set($i, FormConst::OUTPUT_EDIT, Field::BOOLEAN);
        $this->bool = FieldFacade::generate($i);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return $this->bool;
    }
}
