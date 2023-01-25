<b>Anteprima:</b>

<table class="table table-sm">
    @foreach (collect($cols) as $_i)
        @php
            data_set($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::OUTPUT, \Nabre\Quickadmin\Repositories\Form\Field::STATIC);
            data_set($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::VALUE, data_get($_row, data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::VARIABLE)));
        @endphp
        <tr>
            <td>{{ data_get($_i, 'label') }}:</td>
            <td> @include('nabre-quickadmin::livewire.form-manage.item')</td>
        </tr>
    @endforeach
</table>
