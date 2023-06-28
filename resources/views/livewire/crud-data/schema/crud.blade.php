@php
    $_i = $_i ?? null;
    $_modal = [];
    $CRUD_SETTING = collect($CRUD_SETTING ?? $this->crudSetting())
        ->filter(fn($i) => in_array(data_get($i, 'f'), $crud))
        ->values()
        ->toArray();
@endphp
<div class="btn-group btn-group-sm" role="group" aria-label="Crud">
    @foreach ($CRUD_SETTING as $_cIt)
        @php
            $bool = true;
            $_f = data_get($_cIt, 'f');
            $_t = data_get($_cIt, 't');
            $_c = data_get($_cIt, 'c');
            $_y = data_get($_cIt, 'y', 'button');
            $_l = data_get($_cIt, 'l');
            $_r = data_get($_cIt, 'r', []);
            $_id = is_null($_id = data_get($_i, '_id')) ? null : ",'" . $_id . "'";

            switch ($_f) {
                case 'create':
                    $bool = !$putItem || !$putCreate;
                    break;
                case 'delete_all_force':
                case 'delete_all_confirm':
                    $_t .= ' <span class="badge bg-dark">' . data_get($count, 'trashForce', 0) . '</span>';
                    break;
            }

            $_action = $_y!='submit'?'wire:click=action(\'' . $_f . '\'' . $_id . ')':null;
        @endphp

        @if ($putModalMode && in_array($_f, ['create', 'edit', 'copy']))
            <button type="button" {{ $_action }} class="{{ $_c }}" title="{{ $_l }}"
                data-bs-toggle="modal" data-bs-target="#modalPut" title="{{ $_l }}">
                {!! $_t !!}
            </button>
        @elseif (count($_r))
            @php
                $_modal[] = $_f;
            @endphp
            <!-- Button trigger modal -->
            <button type="button" class="{{ $_c }}" data-bs-toggle="modal" title="{{ $_l }}"
                data-bs-target="#modal-{{ Str::camel($_f) }}-{{ data_get($_i, '_id') }}" title="{{ $_l }}">
                {!! $_t !!}
            </button>
        @elseif($bool)
            <button type="{{ $_y }}" class="{{ $_c }}" title="{{ $_l }}"
                {{ $_action }}
                {{ in_array($_f, ['delete', 'delete_force', 'delete_all_force', 'cancel']) ? 'data-bs-dismiss=modal' : null }}>
                {!! $_t !!}
            </button>
        @endif
    @endforeach
</div>

@foreach ($_modal as $_m)
    @php
        $_Action = data_get(
            collect($CRUD_SETTING)
                ->where('f', $_m)
                ->first(),
            'r',
            [],
        );
    @endphp
    <!-- Modal -->
    <div class="modal fade" id="modal-{{ Str::camel($_m) }}-{{ data_get($_i, '_id') }}" tabindex="-1"
        aria-labelledby="modal-{{ Str::camel($_m) }}-{{ data_get($_i, '_id') }}-Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">

                        Titolo

                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Conferma
                </div>
                <div class="modal-footer">
                    @include('nabre-quickadmin::livewire.crud-data.schema.crud', [
                        'crud' => $crud,
                        'CRUD_SETTING' => $_Action,
                    ])
                </div>
            </div>
        </div>
    </div>
@endforeach
