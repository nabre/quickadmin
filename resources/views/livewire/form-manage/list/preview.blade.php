<div class="table-responsive">
    <table class="table table-sm table-light">
        <tbody>
            @foreach ($items as $_i)
                <tr>
                    <td>
                        {!! !is_null($label = data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::LABEL)) ? $label . ':' : null !!}
                    </td>
                    <td>
                        @include('nabre-quickadmin::livewire.form-manage.generate')
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
