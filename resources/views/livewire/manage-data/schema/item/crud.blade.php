@php
    $_i = $_i ?? null;
    $_modal = [];
@endphp
<div class="btn-group btn-group-sm" role="group" aria-label="Crud">
    @foreach ($CRUD_SETTING as $_cIt)
        @php
            $_f = data_get($_cIt, 'f');
            $_t = data_get($_cIt, 't');
            $_c = data_get($_cIt, 'c');
            $_y = data_get($_cIt, 'y', 'button');
            $_l = data_get($_cIt, 'l');
            $_id = is_null($_id = data_get($_i, '_id', data_get($curMode, 'str') == 'FE' ? $idData : null)) ? null : ",'" . $_id . "'";
        @endphp
        @if (in_array($_f, $crud))
            @switch($_f)
                @case('delete_confirm')
                    @php
                        $_modal[] = $_f;
                    @endphp
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#confirmDelete-{{ data_get($_i, '_id') }}" title="{{ $_l }}">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                @break

                @case('delete_all_confirm')
                    @php
                        $_modal[] = $_f;
                    @endphp
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteAll"
                        title="{{ $_l }}">
                        <i class="fa-regular fa-trash-can"></i> <span
                            class="badge bg-dark rounded-pill">{{ $_countTF }}</span>
                    </button>
                @break

                @case('save')
                    <button type="{{ $_y }}" class="{{ $_c }}" title="{{ $_l }}">
                        {!! $_t !!}
                    </button>
                @break

                @case('sync')
                    @if (!$syncMsg)
                        <button type="{{ $_y }}" class="{{ $_c }}" title="{{ $_l }}"
                            wire:click="action('{{ $_f }}'{{ $_id }})">
                            {!! $_t !!}
                        </button>
                    @endif
                @break

                @default
                    <button type="{{ $_y }}" class="{{ $_c }}" title="{{ $_l }}"
                        wire:click="action('{{ $_f }}'{{ $_id }})"
                        {{ in_array($_f, ['delete', 'delete_force', 'delete_all_force']) ? 'data-bs-dismiss=modal' : null }}>
                        {!! $_t !!}
                    </button>
            @endswitch
        @endif
    @endforeach
</div>

@foreach ($_modal as $_m)
    @php
        $_delAction = data_get(
            collect($CRUD_SETTING)
                ->where('f', $_m)
                ->first(),
            'r',
            [],
        );
    @endphp
    @switch($_m)
        @case('delete_confirm')
            @php
                $crud = array_intersect(
                    $crud,
                    collect($_delAction)
                        ->pluck('f')
                        ->toArray(),
                );
                $msg_delete = $trashMode ? '- ' . (in_array('delete', $crud) ? 'Spostamento nel cestino' : 'Eliminazione definitva') : null;
            @endphp
            <!-- Modal -->
            <div class="modal fade" id="confirmDelete-{{ data_get($_i, '_id') }}" tabindex="-1"
                aria-labelledby="confirmDelete-{{ data_get($_i, '_id') }}-Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Conferma {{ $msg_delete }}</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @php
                                $_structure = $this->structure()->where(\Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE, '!=', \Nabre\Quickadmin\Repositories\Form2\FormConst::CRUD_VAR_NAME);
                                $_root = 'values.' . ($trashView ? 'TV' : data_get($curMode, 'str')) . (is_null($_id = data_get($_i, '_id')) ? null : '.' . $_id);
                            @endphp
                            @include('nabre-quickadmin::livewire.manage-data.schema.item.crudPreview')
                        </div>
                        <div class="modal-footer">
                            @include('nabre-quickadmin::livewire.manage-data.schema.item.crud', [
                                'crud' => $crud,
                                'CRUD_SETTING' => $_delAction,
                            ])
                        </div>
                    </div>
                </div>
            </div>
        @break

        @case('delete_all_confirm')
            @php
                $crud = ['delete_all_force'];
            @endphp
            <!-- Modal -->
            <div class="modal fade" id="confirmDeleteAll" tabindex="-1" aria-labelledby="confirmDeleteAll-Label"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Conferma - Svuota cestino</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Vengono cancellate <b>{{ $_countTF }}</b> ricorrenze. </p>
                            @if (($rest = $_countT - $_countTF) > 0)
                                <p>
                                    @if ($rest == 1)
                                        Non può essere eliminata <b>{{ $rest }}</b> ricorrenza.
                                    @else
                                        Non possono essere eliminate <b>{{ $rest }}</b> ricorrenze.
                                    @endif
                                </p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            @include('nabre-quickadmin::livewire.manage-data.schema.item.crud', [
                                'crud' => $crud,
                                'CRUD_SETTING' => $_delAction,
                            ])
                        </div>
                    </div>
                </div>
            </div>
        @break

        @default
    @endswitch
@endforeach
