@php
    $title = is_null($putCreate) ? 'Copia' : ($putCreate ? 'Crea' : 'Modifica');
    $_root = 'values';
    $EDIT = true;
    $errorsView = $errorsView ?? false;
@endphp
@if ($putModalMode)
    @include('nabre-quickadmin::livewire.crud-data.schema.put.modal')
@else
    @include('nabre-quickadmin::livewire.crud-data.schema.put.put')
@endif
