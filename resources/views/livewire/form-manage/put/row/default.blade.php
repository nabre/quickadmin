<div class="row">
    <div class="col-md-1 pt-1">
        {{ data_get($_i, 'label') }}:
    </div>
    <div class="col">
        @include('nabre-quickadmin::livewire.form-manage.item')
    </div>
    <div class="col-md-3 pt-1">
        {!! $this->info($_i) !!}
    </div>
</div>
