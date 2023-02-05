<div class="row borber {{ $_ki ? 'border-top' : null }} p-1 mt-1">
    @if ($_item_col >= 2)
        <div class="col-2">
            {!! !is_null($label = data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::LABEL))
                ? $label . ':'
                : null !!}
        </div>
    @endif
    @if ($_item_col >= 1)
        <div class="col">
            @include('nabre-quickadmin::livewire.form-manage.generate')
        </div>
    @endif
    @if ($_item_col >= 3)
        <div class="col-3">

        </div>
    @endif
</div>
