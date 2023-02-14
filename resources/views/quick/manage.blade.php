@extends('nabre-quickadmin::skeleton.html')
@extends('nabre-quickadmin::skeleton.body.left-colum',['DARK'=>true])
@section('LEFT-COL')
{!! menuRender('ManageBar','list-group','nabre-quickadmin::laravel-menu.bootstrap-sidebar-items') !!}
@endsection
