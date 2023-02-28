@if (count($errors) > 0)
    @php
        $_name = data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::OPTIONS_WIREMODEL);
        $_ins = $errors->has($_name) ? $errors->getMessages($_name) : false;
        data_set($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::ERRORS_PRINT, $_ins);
    @endphp
@endif
@php
    $_str=Nabre\Quickadmin\Repositories\Form\Facades\FieldFacade::generate($_i) ;
@endphp
{!! $_str!!}

