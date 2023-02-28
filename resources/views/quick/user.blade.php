@extends('nabre-quickadmin::skeleton.html')
@extends('nabre-quickadmin::skeleton.body.left-colum')
@section('LEFT-COL')
<<<<<<< HEAD
{!! Nabre\Quickadmin\Facades\Repositories\Menu\Page::menu('UserBar','list-group','nabre-quickadmin::laravel-menu.bootstrap-sidebar-items') !!}
=======
{!! menuRender('UserBar','list-group','nabre-quickadmin::laravel-menu.bootstrap-sidebar-items') !!}
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
@endsection
