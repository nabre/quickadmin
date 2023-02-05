@if ($crud)
    @include('nabre-quickadmin::livewire.form-manage.list.head')
@endif
@if ($countActiveIds)
    <div class="table-responsive">
        <table class="table table-sm">
            @if ($head)
                <thead>
                    <tr>
                        @foreach ($items as $_i)
                            <th>@include('nabre-quickadmin::livewire.form-manage.generate')</th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody>
                @foreach ($rows as $_id => $_row)
                    <tr class="@if (in_array($_id, $trashIds)) table-secondary @endif">
                        @foreach ($_row as $_i)
                            <td>@include('nabre-quickadmin::livewire.form-manage.generate')</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    @php
        $_i = collect($items)->last();
        $_bool = data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::OUTPUT) == \Nabre\Quickadmin\Repositories\Form\Field::CRUD;
    @endphp
    <div class="alert alert-info">
        Non ci sono elementi da visualizzare.
        @if ($_bool)
            <hr>
            @include('nabre-quickadmin::livewire.form-manage.generate')
        @endif
    </div>
@endif
