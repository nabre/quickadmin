<div class="btn-group btn-group-sm">
    @can('create', $model)
        <button class="btn btn-success" type="button" wire:click="modePut()">
            <i class="fa-regular fa-square-plus"></i>
        </button>
    @endcan
</div>
