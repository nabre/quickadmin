@extends('nabre-quickadmin::skeleton.html')
@extends('nabre-quickadmin::skeleton.body.left-colum',['DARK'=>true])
@section('LEFT-COL')
{!! Nabre\Quickadmin\Facades\Repositories\Page::menu('ManageBar','list-group','nabre-quickadmin::laravel-menu.bootstrap-sidebar-items') !!}
@endsection
