@if ($putItem)
    <form wire:submit.prevent="action('save')" class="card mb-2">
        <div class="card-header">
            {{ $title }}
        </div>
        <div class="card-body">
            @include('nabre-quickadmin::livewire.crud-data.schema.put.form')
        </div>
        <div class="card-footer">
            @include('nabre-quickadmin::livewire.crud-data.schema.crud', [
                'crud' => ['save','cancel'],
            ])
        </div>
    </form>
@endif
