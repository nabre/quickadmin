@php
    use Collective\Html\HtmlFacade as Html;
    $_ROOT = implode('.', [$_root, $_v]);
    $_VALUE = data_get($this, $_ROOT);
@endphp
@switch(data_get($_col,\Nabre\Quickadmin\Repositories\Form2\FormConst::OUTPUT_EDIT))
    @case(\Nabre\Quickadmin\Repositories\Form2\Field::BOOLEAN)
        {!! $_VALUE
            ? '<i class="fa-regular text-success fa-circle-check"></i>'
            : '<i class="fa-regular text-danger fa-circle-xmark"></i>' !!}
    @break

    @case(\Nabre\Quickadmin\Repositories\Form2\Field::BOOLEAN2)
        {!! $_VALUE ? '<i class="fa-solid text-success fa-check"></i>' : null !!}
    @break

    @default
        @if (is_string($_VALUE))
            {!! $_VALUE !!}
        @elseif(is_array($_VALUE))
            @if (!count($_VALUE))
                {!! \Nabre\Quickadmin\Repositories\Form2\FormConst::STATIC_EMPTY !!}
            @else
                {!! (string) Html::div(
                    collect($_VALUE)->map(function ($v) {
                            return (string) Html::tag('li', $v, ['class' => 'list-group-item p-0 ps-1 pe-1 m-0']);
                        })->implode(''),
                    ['class' => 'list-group text-sm p-0 m-0'],
                ) !!}
            @endif
        @elseif(is_bool($_VALUE))
            {!! $_VALUE
                ? '<i class="fa-regular text-success fa-circle-check"></i>'
                : '<i class="fa-regular text-danger fa-circle-xmark"></i>' !!}
        @elseif(is_null($_VALUE))
            {!! \Nabre\Quickadmin\Repositories\Form2\FormConst::STATIC_EMPTY !!}
        @else
            {{ var_export($_VALUE) }}
        @endif
@endswitch
