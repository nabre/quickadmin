<?php

namespace Nabre\Quickadmin\Repositories\Menu;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
use Lavary\Menu\Builder as MenuBuilder;
use Lavary\Menu\Collection;

class Builder extends MenuBuilder
{

    public function __construct($name, $conf)
    {
        $this->name = $name;

        // creating a laravel collection for storing menu items
        $this->items = new Collection();

        $this->conf = $conf;
    }

    public function add($title, $options = '')
    {
        $id = isset($options['id']) ? $options['id'] : $this->id();

        $item = new Item($this, $id, $title, $options);

        $this->items->push($item);

        return $item;
    }
}
