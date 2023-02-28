@extends('nabre-quickadmin::skeleton.html')
@extends('nabre-quickadmin::skeleton.body.left-colum')
@section('LEFT-COL')
{!! menuRender('UserBar','list-group','nabre-quickadmin::laravel-menu.bootstrap-sidebar-items') !!}
@endsection
