<?php

namespace Nabre\Quickadmin\Http\Livewire;

use Livewire\Component;
use Nabre\Quickadmin\Repositories\Form\Form;
use Illuminate\Support\Facades\URL;

class ManageDatabaseCRUD extends Component
{
    var $idData;
    var string $view = '';
    var bool $back = true;
    var bool $crud = true;
    var bool $trashsoft = true;
    var bool $onlyRead = true;

    var array $rules = [];
    var array $items = [];
    var array $values = [];
    var array $rows = [];
    var bool $trashIcon = true;
    var array $trashIds = [];
    protected array $activeIds = [];

    var string $formClass = '';
    var bool $head = true;
    var int $countTrashIds = 0;
    var int $countActiveIds = 0;

    protected $formCompiled;
    protected $setFormParams = [
        'idData',
        'view',
        'back',
        'crud',
        'trashsoft',
        'onlyRead'
    ];

    protected $setDefineParams = [
        'idData',
        'view',
        'back',
        'crud',
        'trashsoft',
        'onlyRead',
        'rules',
        'items',
        'values',
        'rows',
        'trashIcon',
        'trashIds',
        'activeIds'
    ];

    protected $countCalculation = [
        'trashIds' => 'countTrashIds',
        'activeIds' => 'countActiveIds',
    ];

    function mount()
    {
        $this->defaultSettings();
        $this->define();
    }

    private function defaultSettings()
    {
        $params = (new $this->formClass)->readSettings($this->idData);
        collect($params)->each(function ($v, $k) {
            $this->$k = $v;
        });
    }

    function put($id = null)
    {
        $this->view = 'form';
        $this->onlyRead = false;
        $this->idData = $id;
        $this->define();
        $this->changeUrl(Url::previous() . "/" . ($id ?? 'create'));
    }

    function back()
    {
        if (!$this->back) {
            return;
        }

        if ($this->view == 'form') {
            $this->changeUrl(substr($url = Url::previous(), 0, strrpos($url, "/")));
            $this->onlyRead = true;
            $this->view = 'list';
        }

        $this->resetErrorBag();
        $this->define(true);
    }

    function submit()
    {
        $fn = $this->form()->submit();

        if ((bool)$fn($this->formCompiled, $this->values)) {
            $values = data_get($this->validate(), 'values', []);
            if ($this->view == 'form-list') {
                collect($values)->each(function ($values) {
                    $this->form()->save($values);
                });
            } else {
                $this->form()->save($values);
            }
        }

        if ($this->back) {
            $this->idData = null;
        }
        $this->back();
    }

    function refresh()
    {
        (new $this->formClass)->runRefresh();
        $this->back();
    }

    function delete($id)
    {
        $this->find($id)->delete();
        $this->define(true);
    }

    function delete_force($id)
    {
        $this->find($id)->forceDelete();
        $this->define(true);
    }

    function restore($id)
    {
        $this->find($id)->restore();
        $this->define(true);
    }

    public function render()
    {
        return view('nabre-quickadmin::livewire.form-manage.index');
    }

    function trashStatus()
    {
        $this->define(true);
    }

    private function find($id)
    {
        $find = $this->form()->getModel();
        $find = new $find;

        if (method_exists($find, 'trashed')) {
            $find = $find->withTrashed();
        }

        $find = $find->find($id);
        return optional($find);
    }

    private function define($force = false)
    {
        $param = $this->form($force)->array();
        collect($this->setDefineParams)->each(function ($var) use ($param) {
            $this->$var = data_get($param, $var);
        });

        collect($this->countCalculation)->each(function ($count, $array) {
            $this->$count = count($this->$array);
        });
    }

    protected function form($force = false): Form
    {
        if (is_null($this->formCompiled) || $force || $this->formChanged()) {
            $fc = new $this->formClass;
            collect($this->setFormParams)
                ->each(function ($v) use (&$fc) {
                    $fc->$v = $this->$v;
                });
            $fc->input($this->idData);
            $this->formCompiled = $fc;
        }

        return $this->formCompiled;
    }

    private function formChanged()
    {
        return collect($this->setFormParams)->filter(fn ($v) => $this->formCompiled->$v != $this->$v)->count();
    }

    private function changeUrl($url)
    {
        $this->emit('urlChange', $url);
    }
}
