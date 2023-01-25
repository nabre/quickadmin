@extends('nabre-quickadmin::skeleton.html')
@extends('nabre-quickadmin::skeleton.body.left-colum')
@section('LEFT-COL')
{!! Nabre\Quickadmin\Facades\Repositories\Page::menu('UserBar','list-group','nabre-quickadmin::laravel-menu.bootstrap-sidebar-items') !!}
@endsection
