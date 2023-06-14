@php
    $_structure = $_structure ?? $this->structure();
    $_root = $_root ?? 'values.' . data_get($this->curMode, 'str');
@endphp
{{ $title }}
<form wire:submit.prevent="action('save','{{ $idData }}')" class="container">
    @php
        $_col = $_structure->where(\Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE, \Nabre\Quickadmin\Repositories\Form2\FormConst::CRUD_VAR_NAME)->first();
        $_crud = (array) data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::VALUE, []);
    @endphp
    @if (count($_crud))
        <div class="row">
            <div class="col pb-2">
                @include('nabre-quickadmin::livewire.manage-data.schema.item.crud', [
                    'crud' => $_crud,
                ])
            </div>
        </div>
    @endif
    @foreach ($_structure as $_col)
        <div class="row pb-2">
            <div class="col-lg-2 col-md-3">
                @switch(data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE))
                    @case(\Nabre\Quickadmin\Repositories\Form2\FormConst::CRUD_VAR_NAME)
                    @break

                    @default
                        {{ data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE) }}:
                @endswitch
            </div>
            <div class="col">
                @php
                    $_v = data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE);
                @endphp
                @include('nabre-quickadmin::livewire.manage-data.schema.item.item')
            </div>
            @if (
                $write &&
                    data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE) !=
                        \Nabre\Quickadmin\Repositories\Form2\FormConst::CRUD_VAR_NAME)
                <div class="col-md-3">
                    @include('nabre-quickadmin::livewire.manage-data.schema.item.type')
                </div>
            @endif
        </div>
    @endforeach
</form>
