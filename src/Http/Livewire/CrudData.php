<?php

namespace Nabre\Quickadmin\Http\Livewire;

use Livewire\Component;
use Nabre\Quickadmin\Repositories\Form2\FormConst;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use Nabre\Quickadmin\Repositories\Form2\Field;

class CrudData extends Component
{
    use WithPagination;

    var string $formClass;
    public $items = [],
        $values = [],
        $putCreate = null,
        $count,
        $paginate = 20;
    public bool $putItem = false;
    public bool $errorsView = false;
    public bool  $putModalMode = true;

    protected $paginationTheme = 'bootstrap';
    protected bool $trashMode = false;
    protected bool $trashModeView = false;
    protected $class;
    protected $query;
    protected $elements;
    protected $keyName;
    protected $CRUD_SETTING = [
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
            'y' => 'submit',
            't' => '<i class="fa-regular fa-floppy-disk"></i>',
            'l' => 'Salva',
        ],
        [
            'f' => 'cancel',
            'c' => 'btn btn-secondary',
            't' => '<i class="fa-solid fa-xmark"></i>',
            'l' => 'Annulla',
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
            'c' => 'btn btn-danger',
            't' => '<i class="fa-regular fa-trash-can"></i>',
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
            'c' => 'btn btn-danger',
            't' => '<i class="fa-regular fa-trash-can"></i>',
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

    function crudSetting()
    {
        return $this->CRUD_SETTING;
    }

    function getFields()
    {
        return collect($this->elements->pluck(FormConst::VARIABLE))->push('_id')->unique()->toArray();
    }

    function getParams()
    {
        return $this->elements->reject(fn ($i) => data_get($i, FormConst::VARIABLE) == FormConst::CRUD_VAR_NAME)->values();
    }

    function getCols()
    {
        return $this->elements;
    }

    function getList()
    {
        $this->query = $this->class->callQuery($this->getTrash())->paginate($this->paginate);

        $this->items = $this->query->map(function ($i) {
            return collect($this->itemValue($i, false))->sortBy(function ($value, $key) {
                return (string) $key;
            }, SORT_STRING | SORT_FLAG_CASE)->toArray();
        });
    }

    function getLinks()
    {
        if (method_exists($this->query, 'links')) {
            return $this->query->links();
        }
    }

    function rules()
    {
        return $this->elements->pluck(FormConst::RULES, FormConst::VARIABLE)->filter()->put('_id', ['nullable'])->mapWithKeys(function ($v, $k) {
            return ["values." . $k => collect($v)->map(function ($i) {
                return str_replace('{{idData}}', data_get($this, 'values._id'), $i);
            })->toArray()];
        })->toArray();
    }

    function switchTrash($bool)
    {
        if ($this->trashSwitchable()) {
            $this->trashModeView = $bool;
            $this->action('cancel');
            $this->getList();
        }
    }

    function render()
    {
        return view('nabre-quickadmin::livewire.crud-data.index');
    }

    function getTrash()
    {
        return $this->trashMode && $this->trashModeView;
    }

    function getTrashEnable()
    {
        return $this->trashMode;
    }

    function trashSwitchable()
    {
        return $this->getTrashEnable() && data_get($this, 'count.trash');
    }

    function booted()
    {
        $this->classParams();
    }

    function mount()
    {
    }

    function action(string $name, ?string $id = null)
    {
        switch ($name) {
            case "create":
            case "edit":
                $this->values = $this->itemValue(($model = $this->class->getModel())::find($id) ?? $model::make(), true);
                data_set($this, 'putItem', true);
                data_set($this, 'putCreate', is_null(data_get($this, 'values._id')));
                break;
            case "copy":
                $appendCopyField = data_get($this->elements->where(FormConst::OUTPUT_EDIT, Field::TEXT)->first(), FormConst::VARIABLE);
                if (!is_null($appendCopyField)) {
                    $data = $this->itemValue(($model = $this->class->getModel())::find($id) ?? $model::make(), true);
                    data_set($data, '_id', null);
                    $newValue = data_get($data, $appendCopyField) . " (copy)";
                    data_set($data, $appendCopyField, $newValue);
                    data_set($this, 'values', $data);
                    data_set($this, 'putItem', true);
                    data_set($this, 'putCreate', null);
                }
                break;
            case "cancel":
                data_set($this, 'values', []);
                data_set($this, 'putItem', false);
                data_set($this, 'putCreate', null);
                data_set($this, 'errorsView', false);
                break;
            case "save":
                data_set($this, 'errorsView', true);
                $attributes = $this->getParams()->pluck(FormConst::LABEL, FormConst::VARIABLE)->mapWithKeys(function ($v, $k) {
                    return ["values." . $k => $v];
                })->toArray();

                $data = data_get($this->validate(null, [], $attributes), 'values', []);
                $this->class->eloquent()->recursiveSave($data);

                if($this->putModalMode){
                    $this->dispatchBrowserEvent('close-modal');
                }
                $this->action('cancel');
                $this->countRefresh();
                $this->getList();
                break;
            case "sync":
                $this->class->syncCall();
                $this->countRefresh();
                $this->getList();
                break;
            case "delete":
                $this->class->getModel()::findOrFail($id)->delete();
                $this->countRefresh();
                $this->getList();
                break;
            case "delete_force":
                $this->class->getModel()::onlyTrashed()->find($id)->forceDelete();
                $this->countRefresh();
                if (data_get($this, 'count.trash', 0) > 0) {
                    $this->trashModeView = true;
                }
                $this->getList();
                break;
            case "delete_all_force":
                $this->trashForceEnabledItems()->map->forceDelete();

                $this->countRefresh();
                if (data_get($this, 'count.trash', 0) > 0) {
                    $this->trashModeView = true;
                }
                $this->getList();
                break;
            case "restore":
                $this->class->getModel()::onlyTrashed()->find($id)->restore();
                $this->countRefresh();
                if (data_get($this, 'count.trash', 0) > 0) {
                    $this->trashModeView = true;
                }
                $this->getList();
                break;
            default:
                abort(500);
        }
    }

    private function classParams()
    {
        $this->class = new $this->formClass;
        $this->elements = $this->class->buildStructure()->getElements();
        $this->countRefresh();
        $this->getList();
    }

    private function countRefresh()
    {
        data_set($this, 'count.items', $this->class->countResults(false));
        data_set($this, 'count.trash', $this->class->countResults(true));
        $this->trashMode = data_get($this, 'count.trash') !== false;
        if ($this->trashMode) {
            data_set($this, 'count.trashForce', $this->trashForceEnabledItems()->count());
        }
    }

    private function trashForceEnabledItems()
    {
        return $this->class->callQuery(true)->filter(function ($i) {
            return Gate::inspect('delete_force', $i)->allowed();
        });
    }

    private function itemValue($i, $write = true)
    {
        return $this->class->variablesView($i, $this->getFields(), $write);
    }
}
