@foreach ($this->getParams() as $_col)
    <div class="row mb-2">
        <div class="col-lg-2 col-md-3">
            {{ data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::LABEL) }}:
        </div>
        <div class="col">
            @php
                $_v = data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE);
            @endphp
            @include('nabre-quickadmin::livewire.crud-data.schema.put.item')</div>

        <div class="col-md-3">
            @include('nabre-quickadmin::livewire.crud-data.schema.put.type')
        </div>
    </div>
@endforeach
