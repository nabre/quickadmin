<div class="container-fluid bg-light">
@foreach ($items as $_ki=>$_i)
    @switch(data_get($_i,\Nabre\Quickadmin\Repositories\Form\FormConst::OUTPUT))
        @case(\Nabre\Quickadmin\Repositories\Form\Field::HTML)
        @case(\Nabre\Quickadmin\Repositories\Form\Field::BACK)
            @php
                $_item_col = 1;
            @endphp
        @break
        @case(\Nabre\Quickadmin\Repositories\Form\Field::EMBEDS_ONE)
        @case(\Nabre\Quickadmin\Repositories\Form\Field::EMBEDS_MANY)
            @php
                $_item_col = 2;
            @endphp
        @break
        @default
            @php
                $_item_col = 3;
            @endphp
    @endswitch
    @include('nabre-quickadmin::livewire.form-manage.form.i')
@endforeach
</div>

