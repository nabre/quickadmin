@foreach ($items as $item)
    <li @lm_attrs($item) @if ($item->hasChildren()) class="nav-item dropdown" @endif @lm_endattrs>
        @if ($item->link)
            <a @lm_attrs($item->link) @if ($item->hasChildren())
                class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false"
            @else
                class="nav-link"
                @endif @lm_endattrs @if (!$item->hasChildren())
                    href="{!! $item->url() !!}"
                @endif >
                {!! $item->title !!}
            </a>
        @else
            <span class="navbar-text">{!! $item->title !!}</span>
        @endif
        @if ($item->hasChildren())
            <ul class="dropdown-menu">
                @include('nabre-quickadmin::laravel-menu.bootstrap-navbar-items', ['items' => $item->children()])
            </ul>
        @endif
    </li>
    @if ($item->divider)
        <li{!! Lavary\Menu\Builder::attributes($item->divider) !!}>
            </li>
    @endif
@endforeach
