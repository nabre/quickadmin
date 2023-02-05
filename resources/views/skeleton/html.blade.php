<!DOCTYPE html />
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('nabre-quickadmin::skeleton.code.header')
    @livewireStyles
</head>

<body>
    @yield('BODY')

    @include('nabre-quickadmin::skeleton.code.footer')
    @livewireScripts
</body>
</html>
