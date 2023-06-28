@if (count($items) > 0)
    <div class="table-responsive">
        <table class="table">
            <thead>
                @php
                    $_root = 'items';
                    $_crud = (array) data_get(
                        $this->getCols()
                            ->where(\Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE, \Nabre\Quickadmin\Repositories\Form2\FormConst::CRUD_VAR_NAME)
                            ->first(),
                        \Nabre\Quickadmin\Repositories\Form2\FormConst::VALUE,
                        [],
                    );
                @endphp
                <tr>
                    @foreach ($this->getCols() as $_col)
                        <th>
                            @if (data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE) ==
                                    \Nabre\Quickadmin\Repositories\Form2\FormConst::CRUD_VAR_NAME)
                                @if (!$this->getTrash())
                                    @include('nabre-quickadmin::livewire.crud-data.schema.crud', [
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

                @foreach ($items as $_k => $_i)
                    @php
                        $_id = data_get($_i, '_id');
                    @endphp
                    <tr class="{{ data_get($_i, '_id') == data_get($values, '_id') ? 'table-info' : null }}">
                        @foreach ($this->getCols() as $_col)
                            <td>
                                @php
                                    $_v = $_k . '.' . data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::VARIABLE);
                                @endphp
                                @include('nabre-quickadmin::livewire.crud-data.schema.put.item')
                            </td>
                        @endforeach
                    </tr>
                @endforeach

            </tbody>
        </table>

    </div>{!! $this->getLinks() !!}
@elseif($this->getTrash())
    <p class="alert alert-info">
        Il cestino è vuoto
    </p>
@else
    <p>
        @include('nabre-quickadmin::livewire.crud-data.schema.crud', [
            'crud' => ['create'],
        ]) Nessun risultato.
    </p>
@endif
