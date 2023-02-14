<form wire:submit.prevent="submit" class="container">
    @include('nabre-quickadmin::livewire.form-manage.list.index')
    @php
        data_set($_i, \Nabre\Quickadmin\Repositories\Form\FormConst::OUTPUT, \Nabre\Quickadmin\Repositories\Form\Field::SUBMIT);
    @endphp
    @if (count($rules))
        @include('nabre-quickadmin::livewire.form-manage.generate')
    @endif
</form>
