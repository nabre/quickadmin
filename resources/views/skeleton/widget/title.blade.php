@if (!is_null($title = Nabre\Quickadmin\Facades\Repositories\Page::titlePage() ))
<div class="{{ $class??'h2' }}">{!! $title !!}</div>
@endif

