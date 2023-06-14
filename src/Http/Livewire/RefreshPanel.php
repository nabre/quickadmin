<?php

namespace Nabre\Quickadmin\Http\Livewire;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;
use Livewire\Component;
use Nabre\Quickadmin\Repositories\Form\Form;
use Illuminate\Support\Facades\URL;

class RefreshPanel extends Component
{
    var array $artisan = [];
    private $fn = 'nabrequickadmin:optimize';

    function mount()
    {
    }

    function button()
    {
        $output = new BufferedOutput;
        $exitCode = Artisan::call($this->fn(), [], $output);
        if (!$exitCode) {
            $this->artisan = collect(explode("\n", $output->fetch()))->reject(fn ($i) => empty($i))->toArray();
        } else {
            $this->artisan = [];
        }
    }

    function fn()
    {
        return $this->fn;
    }

    public function render()
    {
        return view('nabre-quickadmin::livewire.refresh-panel.index');
    }
}
