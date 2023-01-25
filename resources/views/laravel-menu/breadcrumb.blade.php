@foreach ($items as $item)
    <li @lm_attrs($item) class="breadcrumb-item" @lm_endattrs>
        @if ($item->link && !$item->active)
            <a @lm_attrs($item->link)  @lm_endattrs href="{!! $item->url() !!}">
                {!! $item->title !!}
            </a>
        @else
            <span class="navbar-text">{!! $item->title !!}</span>
        @endif
    </li>
@endforeach
