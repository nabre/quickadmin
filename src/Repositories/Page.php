<?php

namespace Nabre\Quickadmin\Repositories;

use Collective\Html\HtmlFacade as Html;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Menu;

class Page
{
    function add(&$menu, $route,?string $parent=null, bool $boolIcon = false, bool $boolText = false)
    {
        $class = config('routeicons.' . $route);
        if (is_null($class)) {
            $boolIcon = false;
        }

        $icon = $boolIcon ? (Html::tag('i', null, compact('class')) ?? null) : null;
        if (is_null($icon)) {
            $boolText = true;
        }

        $title = __('nabre-quickadmin::route.' . $route) ?? $route;
        $text = $boolText ? (($boolIcon ? ' ' : null) . $title) : null;

        $menu->add($text, compact('route', 'title','parent'))
            ->prepend($icon)->nickname($route);

        return $menu->get($route)->id;
    }

    function breadcrumbs($name='Breadcrumbs'){
        $menu=$this->menuPrint($name);
        if(is_null($menu) || ($bread=$menu->crumbMenu())->all()->count()<=1){
            return optional();
        }
        return $bread;
    }

    function menuPrint($name){
        $menu=$this->getMenu($name);
        if(!$this->menuCheck($menu)){
            return null;
        }
        return $menu;
    }

    protected function getMenu($name){
        return Menu::get($name);
    }

    protected function menuCheck($menu){
        return !is_null($menu);
    }

    function middleware(array $middleware)
    {
        $user = Auth::user();
        foreach ($middleware as $mid) {
            @list($auth, $name) = explode(":", $mid);
            switch ($auth) {
                case "auth":
                    if (!Auth::check()) {
                        return false;
                    }
                    break;
                case "verified":
                    if (is_null($user) || !$user->hasVerifiedEmail()) {
                        return false;
                    }
                    break;
                case "role":
                    if (is_null($user) || !$user->hasAnyRole($name)) {
                        return false;
                    }
                    break;
                case "permission":
                    if (is_null($user) || !$user->hasPermissionTo($name)) {
                        return false;
                    }
                    break;
                case "guest":
                    if (Auth::check()) {
                        return false;
                    }
                    break;
                case "web":
                    break;
                case "registration":

                    break;
                    /*    case "usersettingcompile":
                    if (!Pages::userSettingCompile()) {
                        return false;
                    }
                    break;*/
                case "abort":
                    if (!in_array($name, [401, 403, 200])) {
                        return false;
                    }
                    break;
            }
        }

        return true;
    }

    public function route($name)
    {
        $route = Route::getRoutes()->getByName($name);

        if (is_null($route)) {
            return false;
        }
        return $this->middleware($route->middleware());
    }
}
