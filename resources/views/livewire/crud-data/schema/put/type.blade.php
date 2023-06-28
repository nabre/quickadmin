@php
    $info = data_get($_col, \Nabre\Quickadmin\Repositories\Form2\FormConst::RULES);
@endphp
@if (!is_null($info))
    @foreach ($info as $_o)
        @php
            $_pointD = strpos($_o, ':');
            $_type = $_o;
            $_param = null;
            if ($_pointD !== false) {
                $_type = substr($_o, 0, $_pointD);
                $_param = explode(',', substr($_o, $_pointD + 1));
            }
        @endphp
        @switch($_type)
            @case('required')
                <div class="badge alert alert-danger p-1 m-0" title="{{ $_type }}"><i class="fa-solid fa-star-of-life"></i>
                </div>
            @break

            @case('nullable')
            @break

            @default
                <div class="badge alert alert-secondary p-1 m-0" title="{{ $_type }}">{{ $_type }}</div>
        @endswitch
    @endforeach
@endif
