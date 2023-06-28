@php
    $EDIT=$EDIT??false;
@endphp
@switch(data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE))
    @case(\Nabre\Quickadmin\Repositories\Form2\FormConst::CRUD_VAR_NAME)
        @include('nabre-quickadmin::livewire.crud-data.schema.crud', [
            'crud' => data_get(data_get($this, $_root), $_v, []),
        ])
    @break

    @default
        @if (count($_errors = data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::ERRORS, [])))
            <div class="alert alert-warning p-1">
                @foreach ($_errors as $_e)
                    <p>{{ $_e }}</p>
                @endforeach
            </div>
        @else
            @switch($EDIT?
                data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::OUTPUT_EDIT):
                data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::OUTPUT_VIEW))
                @case(\Nabre\Quickadmin\Repositories\Form2\Field::STATIC)
                    @include('nabre-quickadmin::livewire.crud-data.schema.put.static')
                @break

                @default
                    {!! Nabre\Quickadmin\Repositories\Form2\Facades\FieldFacade::generate($_col, $errorsView?$errors:false, $EDIT, $_root) !!}
            @endswitch
        @endif

    @endswitch
