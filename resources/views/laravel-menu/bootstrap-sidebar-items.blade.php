@foreach ($items as $item)
    @if (!$item->hasChildren())
        <a @lm_attrs($item->link) class="list-group-item" @lm_endattrs
            href="{!! $item->url() !!}">{!! $item->title !!}</a>
    @else
        <li class="list-group-item p-1 pe-0">
            <div class="card">
                <div class="card-header">
                    {!! $item->title !!}
                </div>
                <ul class="list-group list-group-flush">
                    @include('nabre-quickadmin::laravel-menu.bootstrap-sidebar-items', [
                        'items' => $item->children(),
                    ])
                </ul>
            </div>
        </li>
    @endif
    @if ($item->divider)
        <li{!! Lavary\Menu\Builder::attributes($item->divider) !!}>
            </li>
    @endif
@endforeach
