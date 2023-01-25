@php
    $_name = data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::OPTIONS_WIREMODEL) ?? null;
@endphp
@if (count($errors) > 0)
    @if ($errors->has($_name))
        @php
            $_ins = $errors->getMessages($_name);
        @endphp
    @else
        @php
            $_ins = false;
        @endphp
    @endif
    @php
        data_set($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::ERROR_PRINT, $_ins);
    @endphp
@endif
{!! \Nabre\Quickadmin\Repositories\Form\Field::generate($_i) ?? $emptyValue !!}
