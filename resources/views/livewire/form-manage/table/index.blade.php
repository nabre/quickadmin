<div class="row">
    @include('nabre-quickadmin::livewire.form-manage.title')
</div>
@if (!count($itemsTable))
    <div class="alert alert-info">
        <p>La tabella risulta vuota.</p>
        <hr>
        @can('create', $model)
            @include('nabre-quickadmin::livewire.form-manage.table.manage')
        @else
            <p>Non si possono inserire nuovi dati</p>
        @endcan
    </div>
@else
    <div class="table-responsive">
        <table class="table table-sm w-auto">
            <thead>
                <tr>
                    @foreach (collect($cols)->pluck(\Nabre\Quickadmin\Repositories\Form\FormConst::LABEL) as $_h)
                        <th>{{ $_h }}</th>
                    @endforeach
                    <th>
                        @include('nabre-quickadmin::livewire.form-manage.table.manage')
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($itemsTable as $_row)
                    <tr>
                        @foreach (collect($cols) as $_i)
                            @php
                                data_set($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::OUTPUT, \Nabre\Quickadmin\Repositories\Form\Field::STATIC);
                                data_set($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::VALUE, data_get($_row, data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::VARIABLE)));
                            @endphp
                            <td>
                                @include('nabre-quickadmin::livewire.form-manage.item')
                            </td>
                        @endforeach
                        <td>
                            @include('nabre-quickadmin::livewire.form-manage.table.body.manage')
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endif
