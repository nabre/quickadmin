<?php

namespace Nabre\Quickadmin\Repositories\Form;

class FormConst
{
    #
    #stringVariables
    #
    const VARIABLE = ['variable'];
    const OUTPUT = ['output'];
    const OUTPUT_EDIT = ['output_edit'];
    const TYPE = ['type'];
    const CAST = ['cast'];
    const VALUE = ['value'];
    const VALUE_LABEL = ['value_label'];
    const LABEL = ['label'];

    #options
    const OPTIONS = ['set', 'options'];
    const OPTIONS_WIREMODEL = ['set', 'options', 'wire:model'];
    const OPTIONS_CLASS = ['set', 'options', 'class'];

    #iNFO
    const INFO = ['set', 'info'];

    #list
    const LIST = ['set', 'list'];
    const LIST_ITEMS = ['set', 'list', 'items'];
    const LIST_EMPTY = ['set', 'list', 'empty'];
    const LIST_LABEL = ['set', 'list', 'label'];
    const LIST_DISABLED = ['set', 'list', 'disabled'];
    const LIST_SORT = ['set', 'list', 'sort'];

    #embed
    const EMBED = ['embed'];
    const EMBED_DATAKEY = ['embed', 'parent', 'dataKey'];
    const EMBED_MODEL = ['embed', 'parent', 'model'];
    const EMBED_VARIABLE = ['embed', 'parent', 'variable'];
    const EMBED_OUTPUT = ['embed', 'wire', 'output'];
    const EMBED_WIRE_MODEL = ['embed', 'wire', 'model'];
    const EMBED_FORM = ['embed', 'wire', 'form'];
    const EMBED_ELEMENTS=['embed','wire','elements'];
    const EMBED_OWNERKEY=['embed','wire','ownerKey'];

    #Rules / Request
    const RULES_FN=['set','rules','fn'];
    const RULES_PARAMS=['set','rules','params'];

    #Errors
    const ERROR = ['error'];
    const ERROR_PRINT = ['error_print'];

    #relations
    const REL = ['set', 'rel'];
    const REL_MODEL = ['set', 'rel', 'model'];
    const REL_NAME = ['set', 'rel', 'name'];
    const REL_TYPE = ['set', 'rel', 'type'];
    const REL_FK = ['set', 'rel', 'foreignKey'];
    const REL_OK = ['set', 'rel', 'ownerKey'];

    #stringValues
    const EMPTY_KEY = null;

    static function string($const){
        $const=constant('self::'.$const);
       return implode('.',(array)($const ?? []));
    }

    static function request($method)
    {
        return ['set', 'request', $method];
    }

    static function labelSelect()
    {
        return '-nessuna opzione selezionata-';
    }

    static function labelSelectAddNew()
    {
        return '-aggiungi nuovo-';
    }
}
