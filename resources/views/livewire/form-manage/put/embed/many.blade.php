@php
    $_wireModel = data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::OPTIONS_WIREMODEL);
    $_list = collect(data_get($this, $_wireModel))->keys();
    $_moveBool = (bool) $_list->count();
    $_embedStr = collect(explode('.', $_wireModel))
        ->skip(1)
        ->implode('.');
@endphp
<div class="list-group">
    @if (count($_list))
        @foreach ($_list as $_num)
            @php
                $_hrBool = null;
            @endphp
            <li class="list-group-item">
                <div class="row">
                    @if (count($_list) > 1)
                        <div class="col-auto">MOVE</div>
                    @endif
                    <div class="col"> @include('nabre-quickadmin::livewire.form-manage.put.print')</div>
                    @if (!in_array(
                        \Nabre\Quickadmin\Repositories\Form\Rule::required(),
                        data_get($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::request($method), [])) || count($_list) > 1)
                        <div class="col-auto">
                            @include('nabre-quickadmin::livewire.form-manage.put.embed.parts.remove')
                        </div>
                    @endif
                </div>
            </li>
        @endforeach
    @else
        @include('nabre-quickadmin::livewire.form-manage.put.embed.parts.no-result')
    @endif

    @include('nabre-quickadmin::livewire.form-manage.put.embed.parts.add')
</div>
@php
    $_num = null;
@endphp
