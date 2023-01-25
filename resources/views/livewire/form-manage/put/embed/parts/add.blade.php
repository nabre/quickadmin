<button type="button" class="text-center btn-sm list-group-item list-group-item-action list-group-item-secondary"
    title='Aggiungi'>
    <div class="row">
        <div class="col" wire:click="embedItAdd('{{ $_embedStr }}')">
            <i class="fa-regular fa-square-plus">
    </i>
        </div>
        @if ($_selList = data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::LIST_ITEMS, false))
            @php
                //filtra
                $_fKeys = ['*', data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::EMBED_OWNERKEY)];
                $_selected = (array) data_get(data_get($this, data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::OPTIONS_WIREMODEL)), $_fKeys);
                $_selList = collect($_selList)->reject(fn($v, $k) => in_array($k, $_selected));
            @endphp
            <div class="col">
                <select class="form-select" wire:model="selectedAdd" wire:change="embedItAdd('{{ $_embedStr }}')">
                    @foreach ($_selList as $_s => $_ss)
                        <option value="{{ $_s }}">{{ $_ss }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>


</button>
