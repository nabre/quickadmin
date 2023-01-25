@if (isset($$YELD))
    {!! $$YELD !!}
@else
    @yield($YELD, "[[$YELD]]")
@endif
