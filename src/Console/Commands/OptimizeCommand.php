<?php

namespace Nabre\Quickadmin\Console\Commands;

use Illuminate\Foundation\Console\OptimizeClearCommand as Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'nabrequickadmin:optimize')]
class OptimizeCommand extends Command
{
    protected $name = 'nabrequickadmin:optimize';
    protected static $defaultName = 'nabrequickadmin:optimize';
    protected $description = 'Cache the framework bootstrap files';

    public function handle()
    {
        
        parent::{__FUNCTION__}();

        $this->components->info('Syncronize database\'s data');

        collect([
            'permission' => fn () => $this->callSilent('update:permission') == 0,
            'field types' => fn () => $this->callSilent('sync:field-type') == 0,
            'settings' => fn () => $this->callSilent('sync:setting') == 0,
            'user' => fn () => $this->callSilent('update:user') == 0,
        ])->each(fn ($task, $description) => $this->components->task($description, $task));

        $this->newLine();
    }
}
