{!! $_bread = Nabre\Quickadmin\Facades\Repositories\Menu\Page::breadcrumbs() !!}
@if (!empty($_bread))
    <hr>
@else
    <br>
@endif
