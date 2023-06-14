@include('nabre-quickadmin::livewire.manage-data.schema.results')
@php
    $_crud = (array) data_get(
        $this->structure()
            ->where(\Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE, \Nabre\Quickadmin\Repositories\Form2\FormConst::CRUD_VAR_NAME)
            ->first(),
        \Nabre\Quickadmin\Repositories\Form2\FormConst::VALUE,
        [],
    );
@endphp
@if (in_array('sync', $_crud) && $syncMsg)
    <div class="alert alert-primary pe-all" role="alert" wire:click="action('syncmsgclose')" style="cursor: pointer;"
        title="Chiudi messaggio">
        È stata completata la funzione di sincronizzazione.
    </div>
@endif
@if (!data_get($count, data_get($curMode, 'str')) && !$trashView)
    <div class="row">

        <div class="col-auto">
            @php
                $_col = $this->structure()
                    ->where(\Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE, \Nabre\Quickadmin\Repositories\Form2\FormConst::CRUD_VAR_NAME)
                    ->first();
            @endphp
            @include('nabre-quickadmin::livewire.manage-data.schema.item.crud', [
                'crud' => (array) data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::VALUE, []),
            ])

        </div>
        <div class="col">Nessuna voce nel database.</div>
    </div>
@elseif(!$trashMode && $trashView)
    <div class="alert alert-warning">
        L'elenco di informazioni non prevede la funzione cestino.
    </div>
@elseif ($trashView && !data_get($count, 'TV'))
    <div class="alert alert-info">
        Al momento il cestino è vuoto.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-sm w-auto">
            <thead>
                <tr>
                    @foreach ($this->structure() as $_col)
                        <th>
                            @if (data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE) ==
                                    \Nabre\Quickadmin\Repositories\Form2\FormConst::CRUD_VAR_NAME)
                                @if (!$trashView)
                                    @include('nabre-quickadmin::livewire.manage-data.schema.item.crud', [
                                        'crud' => $_crud,
                                    ])
                                @else
                                    #
                                @endif
                            @else
                                {{ data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::LABEL) }}
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $_root = 'values.' . ($trashView ? 'TV' : data_get($this->curMode, 'str'));
                @endphp
                @foreach (data_get($this, $_root, []) as $_i)
                    <tr>
                        @foreach ($this->structure() as $_col)
                            <td>
                                @php
                                    $_v = data_get($_i, '_id') . '.' . data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE);
                                @endphp
                                @include('nabre-quickadmin::livewire.manage-data.schema.item.item')
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
