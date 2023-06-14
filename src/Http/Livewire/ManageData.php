<?php

namespace Nabre\Quickadmin\Http\Livewire;

use Livewire\Component;
use Nabre\Quickadmin\Repositories\Form2\Field;
use Nabre\Quickadmin\Repositories\Form2\FormConst;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

class ManageData extends Component
{
    var string $formClass;
    var $idData;
    var string $MODE = 'LVFEFV';
    var array $curMode = [];
    var string $keyName = '_id';
    var array $customCRUD = [];
    var $pageGetMode = null;
    var bool $errorsView = false;

    var bool $syncMsg = false;
    var bool $trashMode = false;
    var bool $trashView = false;
    var array $count = [];
    var array $values = [];

    //var array $rules = [];

    var bool $write = false;
    var string $title = 'title';

    var $CRUD_SETTING = [
        [
            'f' => 'create',
            'c' => 'btn btn-success',
            't' => '<i class="fa-solid fa-plus"></i>',
            'l' => 'Crea',
        ],
        [
            'f' => 'sync',
            'c' => 'btn btn-dark',
            't' => '<i class="fa-solid fa-retweet"></i>',
            'l' => 'Sincronizza',
        ],
        [
            'f' => 'view',
            'c' => 'btn btn-outline-secondary',
            't' => '<i class="fa-regular fa-eye"></i>',
            'l' => 'Visualizza',
        ],
        [
            'f' => 'edit',
            'c' => 'btn btn-primary',
            't' => '<i class="fa-regular fa-pen-to-square"></i>',
            'l' => 'Modifica',
        ],
        [
            'f' => 'save',
            'c' => 'btn btn-primary',
            't' => '<i class="fa-regular fa-floppy-disk"></i>',
            'y' => 'submit',
            'l' => 'Salva',
        ],
        [
            'f' => 'copy',
            'c' => 'btn btn-outline-dark',
            't' => '<i class="fa-regular fa-copy"></i>',
            'l' => 'Copia',
        ],
        [
            'f' => 'restore',
            'c' => 'btn btn-warning',
            't' => '<i class="fa-solid fa-trash-can-arrow-up"></i>',
            'l' => 'Ripristina',
        ],
        [
            'f' => 'delete_confirm',
            'l' => 'Elimina',
            'r' => [
                [
                    'f' => 'delete',
                    'c' => 'btn btn-danger',
                    't' => '<i class="fa-regular fa-trash-can"></i>',
                    'l' => 'Elimina',
                ],
                [
                    'f' => 'delete_force',
                    'c' => 'btn btn-danger',
                    't' => '<i class="fa-regular fa-trash-can"></i>',
                    'l' => 'Elimina',
                ],
            ]
        ],
        [
            'f' => 'delete_all_confirm',
            'l' => 'Elimina tutto',
            'r' => [
                [
                    'f' => 'delete_all_force',
                    'c' => 'btn btn-danger',
                    't' => '<i class="fa-regular fa-trash-can"></i>',
                    'l' => 'Elimina tutto',
                ],
            ]
        ],
        [
            'f' => 'back',
            'c' => 'btn btn-secondary',
            't' => '<i class="fa-solid fa-angles-left"></i>',
            'l' => 'Indietro',
        ],
    ];

    protected $class;
    protected $prefix;
    protected $modeGetEnabled = ['create', 'view', 'edit', 'trash'];

    var $elements;

    public function mount()
    {
        $this->loadPage();
        $this->inputForm($this->MODE, $this->idData);
    }

    public function mode($mode, $id = null)
    {
        $this->inputForm($mode, $id);
        return;
    }

    function rules()
    {
        return $this->class->setPrefix($this->prefix)->getRules();
    }

    public function updatingTrashView($bool)
    {
        switch ($bool) {
            case true:
                $this->putUrl('trash', null);
                break;
            case false:
                $this->backUrl();
                break;
        }
    }

    function action($action, $id = null)
    {
        $MODE = null;
        $this->class = new $this->formClass($this->MODE);
        switch ($action) {
            case "create":
                $this->title = "Aggiungi";
                $MODE = 'FE' . $this->MODE;
                $this->putUrl('create', $id);
                break;
            case "sync":
                $this->class->syncCall();
                $syncMsg = true;
                $MODE = $this->MODE;
                break;
            case "syncmsgclose":
                break;
            case "view":
                $MODE = 'FV' . $this->MODE;
                $this->putUrl('view', $id);
                break;
            case "save":
                data_set($this, 'errorsView', true);
                $this->prefix = 'values.FE';
                $values = data_get($this->validate(), $this->prefix, []);
                $this->class->eloquent()->recursiveSave($values);
                $MODE = substr($this->MODE, strpos($this->MODE, 'L'), 2) . $this->MODE;
                data_set($this, 'errorsView', false);
                $this->backUrl();
                break;
            case "copy":
                $this->prefix = 'values.LE.' . $id;
                $data = data_get($this->validate(), $this->prefix);
                $appendCopyField = data_get($this->elements->where(FormConst::OUTPUT_EDIT, Field::TEXT)->first(), FormConst::VARIABLE);
                if (!is_null($appendCopyField)) {
                    data_set($data, '_id', null);
                    $newValue = data_get($data, $appendCopyField) . " (copy)";
                    data_set($data, $appendCopyField, $newValue);
                    $copied = $this->class->eloquent()->recursiveSave($data);

                    if (strpos($this->MODE, 'FE') !== false) {
                        $id = data_get($copied, '_id');
                        $MODE .= "FE";
                    }
                    $this->putUrl('edit', $id);
                }
                $MODE .= data_get($this->curMode, 'str') . $this->MODE;
                break;
            case "edit":
                $MODE = 'FE' . $this->MODE;
                $this->putUrl('edit', $id);
                break;
            case "delete":
                $this->class->eloquent()->findOrNew($id)->delete();
                $MODE = "LV" . $this->MODE;
                $this->backUrl();
                break;
            case "restore":
                $this->class->eloquent()->onlyTrashed()->find($id)->restore();
                if (!$this->class->eloquent()->onlyTrashed()->get()->count()) {
                    $this->trashView = false;
                    $this->backUrl();
                }
                $MODE = "LV" . $this->MODE;
                break;
            case "delete_force":
                $this->class->eloquent()->onlyTrashed()->find($id)->forceDelete();
                if (!$this->class->eloquent()->onlyTrashed()->get()->count()) {
                    $this->trashView = false;
                }
                $MODE = $this->MODE;
                break;
            case 'delete_all_force':
                $this->class->eloquent()->onlyTrashed()->get()->filter(function ($i) {
                    return Gate::inspect('delete_force', $i)->allowed();
                })->map->forceDelete();
                if (!$this->class->eloquent()->onlyTrashed()->get()->count()) {
                    $this->trashView = false;
                    $this->backUrl();
                }
                $MODE = $this->MODE;
                break;
            case "back":
                $MODE = substr($this->MODE, strpos($this->MODE, 'L'), 2) . $this->MODE;
                $this->backUrl();
                break;
            default:
                dd('Errore! : azione non valida: ' . $action);
                break;
        }

        $this->syncMsg = $syncMsg ?? false;
        if (!is_null($MODE)) {
            $this->mode($MODE, $id);
        }
    }

    function structure()
    {
        return $this->elements;
    }

    public function render()
    {
        return view('nabre-quickadmin::livewire.manage-data.index');
    }

    function buildViewPageName()
    {
        if (is_null($curMode = $this->curMode)) {
            return 'nabre-quickadmin::livewire.manage-data.error';
        }
        return implode('.', ['nabre-quickadmin::livewire.manage-data.schema', data_get($curMode, 'type', 'L')]);
    }

    private function loadPage()
    {
        $mode = in_array($mode = data_get($this, 'pageGetMode'), $this->modeGetEnabled) ? $mode : null;
        $str = '';
        switch ($mode) {
            case "create":
            case "edit":
                $str = 'FE';
                break;
            case "view":
                $str = 'FV';
                break;
            case "trash":
                $this->trashView = true;
                $str = 'TV';
                break;
        }
        $this->MODE = (strpos($this->MODE, $str) !== false ? $str : null) . $this->MODE;
        return $this;
    }

    private function changeUrl($url)
    {
        $this->emit('urlChange', $url);
    }

    private function backUrl()
    {
        $this->changeUrl($this->topUrl());
        data_set($this, 'pageGetMode', null);
        data_set($this, 'idData', null);
        return $this;
    }

    private function topUrl()
    {
        $url = collect(explode("/", Url::previous()));
        $collect = $url->reverse()->take(2)->filter()->values();
        $Bool['id'] = ($filter = $collect->filter(fn ($v, $k) => $v == $this->idData))->count() + 0;
        $Bool['mode'] = in_array($collect->reject(fn ($v, $k) => in_array($k, $filter->keys()->toArray()))->first(), $this->modeGetEnabled) + 0;
        return $url->reverse()->skip(array_sum($Bool))->skip(1)->reverse()->implode('/');
    }

    private function putUrl($mode = null, $id = null)
    {
        $url = $this->topUrl();
        $this->pageGetMode = $mode;
        $this->idData = $id;
        $str = implode("/", array_filter(compact('url', 'mode', 'id')));
        $this->changeUrl($str);
        return $this;
    }

    private function modeDefine($string = null)
    {
        $array = collect(str_split($string, 2))->unique()->values();
        $this->MODE = $array->implode('');
        return $array->map(function ($str) {
            $type = substr($str, 0, 1);
            $type = in_array($type, ['L', 'T', 'F']) ? $type : 'L';
            $mode = substr($str, 1, 1);
            $mode = in_array($mode, ['V', 'E']) ? $mode : 'V';
            return compact('str', 'type', 'mode');
        })->toArray();
    }

    private function modePAGEselect($mode = null)
    {
        $collect = collect($this->modeDefine($mode ?? ($this->MODE = $this->MODE ?? 'LV')));
        data_set($this, 'curMode', $collect->first());
        return $this;
    }

    private function title()
    {
        data_set($this, 'title', $this->class->getModel());
        return $this;
    }

    private function customCRUD()
    {
        $this->class->setCustomCRUD((array) $this->customCRUD);
        return $this;
    }

    private function inputForm($string = null, $id = null)
    {
        $this->modePAGEselect($string);
        $this->class = new $this->formClass($this->MODE);

        $this->title();
        $this->customCRUD();

        $mode = data_get($this->curMode, 'mode');
        $this->class->setMode($mode);
        data_set($this, 'write', $this->class->getMode());

        if (data_get($this->curMode, 'type') == 'F') {
            data_set($this->values, data_get($this->curMode, 'str'), $this->class->F($id));
        } else {
            foreach ($this->class->L() as $key => $val) {
                data_set($this->values, $key, $val);
                data_set($this->count, $key, optional($val)->count());
            }
            data_set($this, 'trashMode', $this->class->getTrashMode());
        }

        if ($this->trashMode) {
            data_set($this->count, 'TF', $this->class->getEloquent()->onlyTrashed()->get()->filter(function ($i) {
                return Gate::inspect('delete_force', $i)->allowed();
            })->count());
        }

        data_set($this, 'keyName', $this->class->getKeyName());
        data_set($this, 'elements', $this->class->getElements());
        data_set($this, 'idData', $id);
        return $this;
    }
}
