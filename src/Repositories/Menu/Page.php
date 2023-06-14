<?php

namespace Nabre\Quickadmin\Repositories\Menu;

use Collective\Html\HtmlFacade as Html;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Menu;

class Page
{
    function add(&$menu, $route, $parent = null, bool $boolIcon = false, bool $boolText = false)
    {
        if ($parent === false) {
            return false;
        }
        $name = $route;
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

        if (preg_match_all("/\{(.*?[^\?])\}/i", Route::getRoutes()->getByName($name)->uri, $varsRoute) && count($varsRoute = ($varsRoute[1] ?? []))) {
            $varsRoute = collect($varsRoute)->mapWithKeys(function ($str) {
                return [$str => (request()->$str ?? null)];
            })->filter()->toArray();

            if (!count($varsRoute)) {
                return false;
            }
            $route = [$route] + $varsRoute;
        }

        if (is_array($parent)) {
            $options = array_merge(compact('route', 'title'), $parent);
        } else {
            $options = compact('route', 'title', 'parent');
        }

        $menu->add($text, $options)
            ->prepend($icon)->nickname($name);

        return $menu->get($name)->id;
    }

    function breadcrumbs($name = 'Breadcrumbs')
    {
        $menu = $this->menuPrint($name);
        if (is_null($menu) || is_null($menu->active()) || ($bread = optional($menu)->crumbMenu())->all()->count() <= 1) {
            return null;
        }
        return Html::tag(
            'nav',
            Html::tag(
                'ol',
                view('nabre-quickadmin::laravel-menu.breadcrumb', ['items' => $bread->roots()]),
                ['class' => 'breadcrumb']
            ),
            ["aria-label" => "breadcrumb"]
        );
    }

    function menu($name, $class = 'navbar-nav', $view = 'nabre-quickadmin::laravel-menu.bootstrap-navbar-items')
    {
        $menu = $this->menuPrint($name);

        if (is_null($roots = $menu->roots())) {
            return null;
        }

        return Html::tag(
            'ul',
            view($view, ['items' => $roots]),
            compact('class')
        );
    }

    function titlePage($name = 'Breadcrumbs')
    {
        $active = optional(optional($this->menuPrint($name))->active());
        $title = $active->title;
        return $title ?? null;
    }

    function menuPrint($name)
    {
        $menu = $this->getMenu($name);
        if (!$this->menuCheck($menu)) {
            return null;
        }
        return $menu;
    }

    protected function getMenu($name)
    {
        return Menu::get($name);
    }

    protected function menuCheck($menu)
    {
        return !is_null($menu);
    }

    function middleware(array $middleware)
    {
        $user = Auth::user();
        foreach ($middleware as $mid) {
            @list($auth, $name) = explode(":", $mid);
            switch ($auth) {
                case "verified":
                    if (is_null($user) || (!impersonateCheck() && userEmailVerified())) {
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
                case "web":
                    break;
                case "abort":
                    if (!in_array($name, [401, 403, 200])) {
                        return false;
                    }
                    break;
                default:
                    $bool = true;
                    switch ($auth) {
                        case "auth":
                            $bool = Auth::check();
                            break;
                        case "guest":
                            $bool = !Auth::check();
                            break;
                        case "registration":
                            $bool = registerPageEnabled();
                            break;
                        case "user-account":
                            $bool = userAccountEnabled();
                            break;
                        case "user-profile":
                            $bool = userProfileEnabled();
                            break;
                        case "settings-define":
                            $bool = settingsPageEnabled();
                            break;
                        case 'user-contact-model':
                            $bool = userProfileModelExist();
                            break;
                        case 'shop':
                            $bool = shopPageEnabled();
                            break;
                    }
                    if (!$bool) {
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
