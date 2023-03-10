<?php

namespace Nabre\Quickadmin\Setting;

use Nabre\Quickadmin\Setting\Drivers\Database;
use Nabre\Quickadmin\Setting\Drivers\Json;
use Nabre\Quickadmin\Setting\Drivers\Memory;
use Illuminate\Support\Manager as BaseManager;
use Illuminate\Support\Facades\Auth;
use Nabre\Resolvers\UserResolver;

class Manager extends BaseManager
{
    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The application instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct($app = null)
    {
        $this->container = $app ?? app();

        parent::__construct($this->container);
    }

    public function getDefaultDriver()
    {
        return config('setting.driver');
    }

    public function createJsonDriver()
    {
        $path = config('setting.json.path');

        return new Json($this->container['files'], $path);
    }

    public function createDatabaseDriver()
    {
        $connection = $this->container['db']->connection(config('setting.database.connection'));
        $table = config('setting.database.table');
        $key = config('setting.database.key');
        $value = config('setting.database.value');
        $encryptedKeys = config('setting.encrypted_keys');
        $user= $this->container['auth']->user();
        return new Database($connection, $table, $key, $value, $encryptedKeys);
    }

    public function createMemoryDriver()
    {
        return new Memory();
    }

    public function createArrayDriver()
    {
        return $this->createMemoryDriver();
    }
}
