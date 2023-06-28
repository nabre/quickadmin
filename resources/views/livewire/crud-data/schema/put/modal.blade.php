<!-- Modal -->
<div wire:ignore.self class="modal fade"  data-bs-backdrop="static" data-bs-keyboard="false" id="modalPut" tabindex="-1" aria-labelledby="modalPut-Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <form wire:submit.prevent="action('save')" class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">

                    {{ $title }}

                </h1>
                <button wire:click="action('cancel')" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('nabre-quickadmin::livewire.crud-data.schema.put.form')
            </div>
            <div class="modal-footer">
                @include('nabre-quickadmin::livewire.crud-data.schema.crud', [
                    'crud' => ['save'],
                ])
            </div>
        </form>
    </div>
</div>
