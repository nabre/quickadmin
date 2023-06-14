@php
    $_countT = data_get($count, 'TV');
    $_countTF = data_get($count, 'TF');
    $_countL = data_get($count, data_get($this->curMode, 'str'));
@endphp
<div class="row">
    <div class="col-auto">
        @if ($trashMode || $trashView)
            <ul class="list-group ">
                <li
                    class="list-group-item {{ $_countT ? 'list-group-item-action' : null }} p-1 {{ $trashView ? null : 'list-group-item-primary' }}">
                    @if ($_countT || $trashView)
                        <input wire:model='trashView' class="form-check-input" type="radio" value="0" id="trash-0">
                    @endif

                    <label class="form-check-label" for="trash-0">
                        <i class="fa-solid fa-list"></i>
                        <span class="badge bg-primary rounded-pill">{{ $_countL }}</span>
                    </label>
                </li>
                <li
                    class="list-group-item {{ $_countT ? 'list-group-item-action' : null }} p-1 {{ !$trashView ? null : 'list-group-item-primary' }}">
                    @if ($_countT )
                        <input wire:model='trashView' class="form-check-input" type="radio" value="1"
                            id="trash-1">
                    @endif
                    <label class="form-check-label" for="trash-1">
                        <i class="fa-solid fa-trash-can"></i>
                        <span class="badge bg-primary rounded-pill">{{ $_countT }}</span>
                    </label>
                </li>
            </ul>
        @else
            <ul class="list-group ">
                <li class="list-group-item p-1 {{ $trashView ? null : 'list-group-item-primary' }}">
                    <label class="form-check-label" for="trash-0">
                        <i class="fa-solid fa-list"></i>
                        <span class="badge bg-primary rounded-pill">{{ $_countL }}</span>
                    </label>
                </li>
            </ul>
        @endif
    </div>
    <div class="col h3">{{ $title }}</div>
</div>
<hr>
@if ($trashView && $_countTF > 1)
    @include('nabre-quickadmin::livewire.manage-data.schema.item.crud', [
        'crud' => (array) 'delete_all_confirm',
    ])
@endif
