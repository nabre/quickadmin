<div class="btn-group">
    @foreach ($_crud as $_btn)
        @switch($_btn)
            @case('update')
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                    wire:click="put('{{ data_get($value, 'id') }}')">
                    Modifica
                </button>
            @break

            @case('copy')
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Copia
                </button>
            @break

            @case('restore')
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal" wire:click="restore('{{ data_get($value, 'id') }}')">
                    Ripristina
                </button>
            @break

            @case('delete_force')
            @case('delete')
                @if ($_modal == $_btn)
                    @switch($_btn)
                        @case('delete_force')
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                wire:click="delete_force('{{ data_get($value, 'id') }}')">
                                Elimina
                            </button>
                        @break

                        @case('delete')
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                wire:click="delete('{{ data_get($value, 'id') }}')">
                                Elimina
                            </button>
                        @break
                    @endswitch
                @else
                    @switch($_btn)
                        @case('delete_force')
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" data-bs-toggle="modal"
                                data-bs-target="#confirmDestroyForce-{{ data_get($value, 'id') }}">
                                Elimina
                            </button>
                        @break

                        @case('delete')
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" data-bs-toggle="modal"
                                data-bs-target="#confirmDestroy-{{ data_get($value, 'id') }}">
                                Elimina
                            </button>
                        @break
                    @endswitch
                @endif
            @break

            @case('view')
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-bs-toggle="modal"
                    data-bs-target="#View-{{ data_get($value, 'id') }}">
                    Visualizza
                </button>
            @break

            @default
                !!!
        @endswitch
    @endforeach
</div>
