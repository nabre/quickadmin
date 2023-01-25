@php
    $_hrBool = $_hrBool ?? false;
@endphp
@foreach ($printForm as $_i)
    @if (is_string($_i))
        {!! $_i !!}
    @else
        @if ($_hrBool && data_get($_i, 'output') != \Nabre\Quickadmin\Repositories\Form\Field::HIDDEN)
            <hr>
        @else
            @php
                $_hrBool = true;
            @endphp
        @endif

        @php
            if (!is_null($_num ?? null)) {
                //preg_replace('/\*/', $_num, $_i['set']['options']['wire:model.defer'], 1);
                data_set($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::OPTIONS_WIREMODEL, str_replace('*', $_num, data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::OPTIONS_WIREMODEL)));
            }
        @endphp

        @switch(data_get($_i,\Nabre\Quickadmin\Repositories\Form\FormConst::OUTPUT))
            @case(\Nabre\Quickadmin\Repositories\Form\Field::EMBEDS_MANY)
            @case(\Nabre\Quickadmin\Repositories\Form\Field::EMBEDS_ONE)
                @include('nabre-quickadmin::livewire.form-manage.put.row.embed', [
                    'printForm' => data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::EMBED_ELEMENTS),
                ])
            @break

            @case(\Nabre\Quickadmin\Repositories\Form\Field::MSG)
            @case(\Nabre\Quickadmin\Repositories\Form\Field::HTML)
                @php
                    $_hrBool = false;
                @endphp
            @case(\Nabre\Quickadmin\Repositories\Form\Field::HIDDEN)
                @include('nabre-quickadmin::livewire.form-manage.put.row.other')
            @break

            @default
                @include('nabre-quickadmin::livewire.form-manage.put.row.default')
        @endswitch
    @endif
@endforeach
