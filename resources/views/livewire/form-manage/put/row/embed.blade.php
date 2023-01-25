<div class="row">
    <div class="col-md-1 pt-1">
        {{ data_get($_i, 'label') }}:
    </div>
    <div class="col">
        @switch(data_get($_i,'output'))
            @case(\Nabre\Quickadmin\Repositories\Form\Field::EMBEDS_MANY)
                @include('nabre-quickadmin::livewire.form-manage.put.embed.many')
            @break

            @case(\Nabre\Quickadmin\Repositories\Form\Field::EMBEDS_ONE)
                @include('nabre-quickadmin::livewire.form-manage.put.embed.one')
            @break
        @endswitch
    </div>
</div>
