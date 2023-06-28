<div class="row">
    <div class="col">
        @if ($this->getTrash() && data_get($count,'trashForce',0)>1)
        <p>
            @include('nabre-quickadmin::livewire.crud-data.schema.crud', [
                'crud' => ['delete_all_confirm','delete_all_force'],
            ])
        </p>
        @endif
    </div>
</div>
