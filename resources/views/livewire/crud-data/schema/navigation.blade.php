<div class="row">
    <div class="col">
        <ul class="list-group mb-2">
            @php
                $tag = $this->trashSwitchable() ? 'a' : 'li';
            @endphp
            <{{ $tag }} href="#"
                class="list-group-item {{ !$this->getTrash() && $this->getTrashEnable() ? 'active' : null }}"
                wire:click="switchTrash(false)">
                <div class="row">
                    <div class="col-1">
                        <i class="fa-solid fa-list"></i>
                        <span class="badge bg-dark rounded-pill">{{ data_get($count, 'items') }}</span>
                    </div>
                    <div class="col">Lista</div>
                </div>

                </{{ $tag }}>
                @if ($this->getTrashEnable())
                    <{{ $tag }} href="#"
                        class="list-group-item {{ $this->getTrash() ? 'active' : null }}"
                        wire:click="switchTrash(true)">
                        <div class="row">
                            <div class="col-1">
                                <i class="fa-solid fa-trash-can"></i>
                                <span class="badge bg-dark rounded-pill">{{ data_get($count, 'trash') }}</span>
                            </div>
                            <div class="col">
                                {{ !data_get($count, 'trash')?'Cestino vuoto':'Cestino' }}
                            </div>
                        </div>
                        </{{ $tag }}>
                @endif

        </ul>
    </div>
</div>
