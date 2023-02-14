<ul class="list-group mb-3">
    @if ($trashIcon)
        <li
            class="p-0 list-group-item list-group-item-action @if ($trashsoft) list-group-item-dark @endif">
            <label class="form-check-label w-100 m-2" for="trashCheckbox">
                <input class="form-check-input me-1 ms-2" type="radio" value="1" wire:model="trashsoft"
                    wire:change="trashStatus" id="trashCheckbox" @if (!$countTrashIds) disabled @endif>

                <i class="fa-solid fa-trash"></i>

                @if ($countTrashIds)
                    Visualizza il cestino
                @else
                    Cestino vuoto
                @endif
            </label>
            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-secondary">
                {{ $countTrashIds }}
                <span class="visually-hidden">Trash items</span>
            </span>
        </li>
    @endif
    <li
        class="p-0 list-group-item @if ($trashIcon) list-group-item-action @endif @if (!$trashsoft) list-group-item-primary @endif">
        @if ($trashIcon)
            <label class="form-check-label w-100 m-2" for="listCheckbox">
                <input class="form-check-input me-1 ms-2" type="radio" value="0" wire:model="trashsoft"
                    wire:change="trashStatus" id="listCheckbox" @if (!$countTrashIds) disabled @endif>
                Elenco
            </label>
            <span class="position-absolute top-100 start-0 translate-middle badge rounded-pill bg-primary">
                {{ $countActiveIds }}
                <span class="visually-hidden">Current Items</span>
            </span>
        @else
            <p class="m-2">Elenco</p>
            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-primary">
                {{ $countActiveIds }}
                <span class="visually-hidden">Current Items</span>
            </span>
        @endif

    </li>
</ul>
