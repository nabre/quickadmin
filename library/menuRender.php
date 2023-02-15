<?php

use Nabre\Quickadmin\Facades\Repositories\Menu\Page;

function menuRender($name, $class = 'navbar-nav', $view = 'nabre-quickadmin::laravel-menu.bootstrap-navbar-items'){
    return Page::menu($name, $class , $view );
}

