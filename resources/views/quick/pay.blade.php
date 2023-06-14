@extends('nabre-quickadmin::skeleton.html')
@extends('nabre-quickadmin::skeleton.body.right-colum')
@section('RIGHT-COL')
{!! menuRender('ShopBar','list-group','nabre-quickadmin::laravel-menu.bootstrap-sidebar-items') !!}
@endsection
