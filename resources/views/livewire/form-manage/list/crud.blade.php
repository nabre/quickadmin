@switch(count($_crud=data_get($value,'crud')))
    @case(0)
        #
    @break

    @default
        <div class="btn-group btn-group-sm" role="group" aria-label="Crud">
            @foreach ($_crud as $_btn)
                @switch($_btn)
                    @case('create')
                        <button type="button" class="btn btn-success" wire:click="put" tittle="Aggiungi"><i
                                class="fa-regular fa-square-plus"></i>
                        </button>
                    @break

                    @case('refresh')
                        <button type="button" class="btn btn-dark" wire:click="refresh" title="Ricarica"><i
                                class="fa-solid fa-arrows-rotate"></i></button>
                    @break

                    @case('update')
                        <button type="button" class="btn btn-primary" wire:click="put('{{ data_get($value, 'id') }}')"
                            title="Modifica">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                    @break

                    @case('copy')
                        <button type="button" class="btn btn-secondary" title="Copia">
                            <i class="fa-regular fa-copy"> </i>
                        </button>
                    @break

                    @case('restore')
                        <button type="button" class="btn btn-warning" wire:click="restore('{{ data_get($value, 'id') }}')"
                            title="Ripristina"><i class="fa-solid fa-trash-arrow-up"></i></button>
                    @break

                    @case('delete_force')
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#confirmDestroyForce-{{ data_get($value, 'id') }}" title="Elimina">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>

                        <div class="modal fade" id="confirmDestroyForce-{{ data_get($value, 'id') }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content bg-danger-subtle text-emphasis-danger">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Conferma eliminazione</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h3>Anteprima:</h3>
                                        @include(
                                            'nabre-quickadmin::livewire.form-manage.form.items',
                                            ['items' => data_get($value, 'items', [])]
                                        )
                                    </div>
                                    <div class="modal-footer">
                                        @include(
                                            'nabre-quickadmin::livewire.form-manage.list.crud-modal',
                                            ['_modal' => $_btn]
                                        )
                                    </div>
                                </div>
                            </div>
                        </div>
                    @break

                    @case('delete')
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#confirmDestroy-{{ data_get($value, 'id') }}" title="Elimina">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>

                        <div class="modal fade" id="confirmDestroy-{{ data_get($value, 'id') }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content  bg-danger-subtle text-emphasis-danger">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                                            @if (data_get($value, 'trashIcon'))
                                                Sposta nel cestino
                                            @else
                                                Conferma eliminazione
                                            @endif
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h3>Anteprima:</h3>
                                        @include(
                                            'nabre-quickadmin::livewire.form-manage.form.items',
                                            ['items' => data_get($value, 'items', [])]
                                        )
                                    </div>
                                    <div class="modal-footer">
                                        @include(
                                            'nabre-quickadmin::livewire.form-manage.list.crud-modal',
                                            ['_modal' => $_btn]
                                        )
                                    </div>
                                </div>
                            </div>
                        </div>
                    @break

                    @case('view')
                        <button type="button" class="btn btn-light" data-bs-toggle="modal"
                            data-bs-target="#View-{{ data_get($value, 'id') }}" title="Anteprima">
                            <i class="fa-regular fa-eye"></i>
                        </button>

                        <div class="modal fade" id="View-{{ data_get($value, 'id') }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Anteprima</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @include(
                                            'nabre-quickadmin::livewire.form-manage.form.items',
                                            ['items' => data_get($value, 'items', [])]
                                        )
                                    </div>
                                    <div class="modal-footer">
                                        @php
                                            $_crudView = collect($_crud)
                                                ->reject(fn($v) => $v == $_btn)
                                                ->toArray();
                                        @endphp
                                        @include(
                                            'nabre-quickadmin::livewire.form-manage.list.crud-modal',
                                            ['_crud' => $_crudView, '_modal' => $_btn]
                                        )
                                    </div>
                                </div>
                            </div>
                        </div>
                    @break

                    @default
                        !!!
                @endswitch
            @endforeach
        </div>
    @endswitch
