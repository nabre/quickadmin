<?php

namespace Nabre\Quickadmin\Repositories\Form\FormTrait;

use Nabre\Quickadmin\Repositories\Form\Field;
use Nabre\Quickadmin\Repositories\Form\FormConst;

trait Output
{
    static $ruleOutput = ['email' => Field::TEXT, 'password' => [Field::PASSWORD2, Field::PASSWORD]];

    private function output()
    {
        $output = $this->getItemData(FormConst::OUTPUT);
        $type = $this->getItemData(FormConst::TYPE);
        $rules = $this->getItemData(FormConst::RULES_FN, []);
        $enabled = collect([]);

        if ($type != 'fake') {
            if ($this->getItemData(FormConst::VARIABLE) == $this->collection->getKeyName()) {
                $enabled = $enabled->merge([Field::HIDDEN]);
            } else {
                switch ($type) {
                    case false:
                        break;
                    case "fillable":
                        switch ($this->getItemData(FormConst::CAST)) {
                            case PasswordCast::class:
                                $enabled = $enabled->push(Field::PASSWORD);
                                break;
                            case LocalCast::class:
                                $enabled = $enabled->push(Field::TEXT_LANG);
                                break;
                            case SettingTypeCast::class:
                                $enabled = $enabled->push(Field::FIELD_TYPE_LIST);
                                break;
                            case "boolean":
                            case "bool":
                                $enabled = $enabled->push(Field::BOOLEAN);
                                break;
                            case CkeditorCast::class:
                                $enabled = $enabled->push(Field::TEXTAREA_CKEDITOR);
                                break;
                            default:
                                $ruleEnable = array_intersect($rules, array_keys(self::$ruleOutput));
                                if (count($ruleEnable)) {
                                    collect(self::$ruleOutput)->filter(fn ($v, $k) => in_array($k, $ruleEnable))->each(function ($fieldType) use (&$enabled) {
                                        $enabled = $enabled->merge((array)$fieldType);
                                    });
                                } else {
                                    $enabled = $enabled->merge([Field::TEXT, Field::TEXTAREA, Field::TEXTAREA_CKEDITOR, Field::HIDDEN]);
                                }
                                break;
                        }
                        break;
                    case "attribute":
                        $enabled = $enabled->push(Field::STATIC);
                        break;
                    case "relation":
                        switch ($this->getItemData(FormConst::REL_TYPE)) {
                            case "BelongsTo":
                            case "HasOne":
                                $enabled = $enabled->merge([Field::SELECT, Field::RADIO, Field::EMBEDS_ONE]);
                                break;
                            case "BelongsToMany":
                            case "HasMany":
                                $enabled = $enabled->merge([Field::CHECKBOX, Field::SELECT_MULTI, Field::EMBEDS_MANY]);
                                break;
                            case "EmbedsMany":
                                $enabled = $enabled->push(Field::EMBEDS_MANY);
                                break;
                            case "EmbedsOne":
                                $enabled = $enabled->push(Field::EMBEDS_ONE);
                                break;
                        }
                        break;
                }
            }

            $enabled = $enabled->push(Field::STATIC)->push(Field::HIDDEN)->unique()->values();

            if (!$enabled->filter(fn ($str) => $str == $output)->count() && $enabled->count()) {
                $output = $enabled->first();
            }
        }


        $this->setItemData(FormConst::OUTPUT, $output ?? Field::STATIC, true);

        #prepara le informazione necessarie per livewire FormEmbed
        if (in_array($this->getItemData(FormConst::OUTPUT), [Field::EMBEDS_MANY, Field::EMBEDS_ONE])) {
            $this->setItemData(FormConst::EMBED_MODEL, $this->model, true);
            $this->setItemData(FormConst::EMBED_DATAKEY, data_get($this->data, $this->data->getKeyName()), true);
            $this->setItemData(FormConst::EMBED_VARIABLE, $this->getItemData('variable'), true);
            $this->setItemData(FormConst::EMBED_OUTPUT, $this->getItemData('output'), true);
            $this->setItemData(FormConst::EMBED_WIRE_MODEL, $this->getItemData('set.rel.model'), true);
            $this->setItemData(FormConst::EMBED_OWNERKEY, $this->getItemData('set.rel.ownerKey'), true);
        }
        return $this;
    }
}
