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
<<<<<<< HEAD
    protected $setFormParams = ['idData', 'view', 'back', 'crud', 'trashsoft', 'onlyRead'];
    protected $setDefineParams = ['idData', 'view', 'back', 'crud', 'trashsoft', 'onlyRead', 'rules', 'items', 'values', 'rows', 'trashIcon', 'trashIds', 'activeIds'];
=======
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

>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
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
<<<<<<< HEAD

=======
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
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
<<<<<<< HEAD
        if ((bool)$fn($this->formCompiled, $this->values)) {
            $values = data_get($this->validate(), 'values', []);
            if ($this->view == 'form-list') {
                collect($values)->each(function ($i) {
                    $this->form()->save($i);
=======

        if ((bool)$fn($this->formCompiled, $this->values)) {
            $values = data_get($this->validate(), 'values', []);
            if ($this->view == 'form-list') {
                collect($values)->each(function ($values) {
                    $this->form()->save($values);
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
                });
            } else {
                $this->form()->save($values);
            }
        }
<<<<<<< HEAD
=======

        if ($this->back) {
            $this->idData = null;
        }
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
        $this->back();
    }

    function refresh()
    {
<<<<<<< HEAD
        $this->form()->runRefresh();
=======
        (new $this->formClass)->runRefresh();
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
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
<<<<<<< HEAD

        $param = $this->form($force)->array();
        //   dd($param,$this);
=======
        $param = $this->form($force)->array();
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
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
<<<<<<< HEAD
            //  dd($fc);
=======
>>>>>>> 4b302560c1852bff3044a2719c00b9a7293fa870
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
