<?php

namespace Nabre\Quickadmin\Routing;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Str;

class ResourceRegistrar extends OriginalRegistrar
{
    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy','livewire' /*,'pdf'*/];

    function getResourceDefault(){
        return $this->resourceDefaults;
    }

    protected function getResourceMethods($defaults, $options)
    {
        $methods = collect(parent::{__FUNCTION__}( $defaults, $options ));

/*
        if(in_array('edit',$methods)){
            $methods[]='update';
        }

        if(in_array('create',$methods)){
            $methods[]='store';
        }
*/
        if (isset($options['except'])) {
            $methods=$methods->reject(fn($m)=>in_array($m,$options['except']));
        }

        if($methods->filter(fn($m)=>$m=='livewire')->count()){
            $methods=$methods->reject(fn($m)=>$m=='index');
        }

        return array_values(array_intersect($defaults,$methods->unique()->toArray()));
    }

    public function register($name, $controller, array $options = [])
    {
        if (isset($options['parameters']) && ! isset($this->parameters)) {
            $this->parameters = data_get($options,'parameters');
        }

        // If the resource name contains a slash, we will assume the developer wishes to
        // register these resource routes with a prefix so we will set that up out of
        // the box so they don't have to mess with it. Otherwise, we will continue.
        if (Str::contains($name, '/')) {
            $this->prefixedResource($name, $controller, $options);
            return;
        }

        // We need to extract the base resource from the resource name. Nested resources
        // are supported in the framework, but we need to know what name to use for a
        // place-holder on the route parameters, which should be the base resources.
        $base = $this->getResourceWildcard( data_get($options,'key') ?? (last(explode('.', $name))) );

        $defaults = $this-> resourceDefaults;

        $collection = new RouteCollection;

        foreach ($this->getResourceMethods($defaults, $options) as $m) {
            $route = $this->{'addResource'.ucfirst($m)}(
                $name, $base, $controller, $options
            );

            if (isset($options['bindingFields'])) {
                $this->setResourceBindingFields($route, $options['bindingFields']);
            }

            $collection->add($route);
        }

        return $collection;
    }
    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceLivewire($name, $base, $controller, $options)
    {
        $name = $this->getShallowName($name, $options);
        $uri = $this->getResourceUri($name).'/{'.$base.'?}';
        $action = $this->getResourceAction($name, $controller, 'index', $options);
        return $this->router->match(array('GET'),$uri, $action);
    }
}
