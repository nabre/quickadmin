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
    const VALUE_DEFAULT = ['value_default'];
    const LABEL = ['label'];
    const VIEW = ['view'];

    #options
    const OPTIONS = ['options'];
    const OPTIONS_WIREMODEL = [ 'options', 'wire:model'];
    const OPTIONS_CLASS = [ 'options', 'class'];
    const OPTIONS_DISABLED = [ 'options', 'disabled'];
    const OPTIONS_MULTIPLE = [ 'options', 'multiple'];

    #iNFO
    const INFO = [ 'info'];

    #list
    const LIST = ['list'];
    const LIST_ITEMS = ['list','items'];
    const LIST_DISABLED = ['list', 'disabled'];
   /*
    const LIST_QUERY = ['list', 'query'];
    const LIST_ITEMS = ['list', 'items'];
    const LIST_EMPTY = ['list', 'empty'];
    const LIST_LABEL = ['list', 'label'];
    const LIST_DISABLED = ['list', 'disabled'];
    const LIST_SORT_DESC = ['list', 'sortDesc'];*/

    #embed
    const EMBED = ['embed'];
    const EMBED_SORTABLE=['embed_sortable'];
    const EMBED_DATAKEY = ['embed', 'parent', 'dataKey'];
    const EMBED_MODEL = ['embed', 'parent', 'model'];
    const EMBED_VARIABLE = ['embed', 'parent', 'variable'];
    const EMBED_OUTPUT = ['embed', 'wire', 'output'];
    const EMBED_WIRE_MODEL = ['embed', 'wire', 'model'];
    const EMBED_FORM = ['embed', 'wire', 'form'];
    const EMBED_ELOQUENT = ['embed', 'wire', 'eloquent'];
    const EMBED_ELEMENTS = ['embed', 'wire', 'elements'];
    const EMBED_OWNERKEY = ['embed', 'wire', 'ownerKey'];

    #Rules / Request
    const RULES=['rules','prams'];
    const RULES_NAME=['rules','name'];

    #Errors
    const ERRORS = ['errors'];
    const ERRORS_PRINT = ['errors_print'];

    const REQUIRED_PROPS = ['__call','props'];
    const REQUIRED_FN = ['__call','fn'];

    #relations
    const REL = ['relation'];
    const REL_MODEL = ['relation', 'model'];
    const REL_NAME = ['relation', 'name'];
    const REL_TYPE = ['relation', 'type'];
    const REL_FK = ['relation', 'foreignKey'];
    const REL_OK = ['relation', 'ownerKey'];

    #stringValues
    const EMPTY_KEY = null;
    const STATIC_EMPTY='--';

    static function string($const)
    {
        $const = constant('self::' . $const);
        return implode('.', (array)($const ?? []));
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
