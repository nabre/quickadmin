@php
    $_hrBool = null;
    $_wireModel = data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::OPTIONS_WIREMODEL);
    $_embedStr = collect(explode('.', $_wireModel))
        ->skip(1)
        ->implode('.');

    //  dd(get_defined_vars());

@endphp
@if (is_null(data_get($this, $_wireModel)))
    <div class="list-group">
        @include('nabre-quickadmin::livewire.form-manage.put.embed.parts.no-result')
        @include('nabre-quickadmin::livewire.form-manage.put.embed.parts.add')
    </div>
@else
    @if (!in_array(
        \Nabre\Quickadmin\Repositories\Form\Rule::required(),
        data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::request($method), [])))
        <div class="row">
            <div class="col">
                @include('nabre-quickadmin::livewire.form-manage.put.print')
            </div>
            <div class="col-auto">
                @include('nabre-quickadmin::livewire.form-manage.put.embed.parts.remove')
            </div>
        </div>
    @else
        @include('nabre-quickadmin::livewire.form-manage.put.print')
    @endif
@endif
