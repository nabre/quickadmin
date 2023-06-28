<div>

    @include('nabre-quickadmin::livewire.crud-data.schema.put.index')

    <div class="">
        <div class="card">
            <div class="card-body">
                @include('nabre-quickadmin::livewire.crud-data.schema.navigation')
                @include('nabre-quickadmin::livewire.crud-data.schema.action-top')
                @include('nabre-quickadmin::livewire.crud-data.schema.table')
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            window.livewire.on('urlChange', param => {
                history.pushState(null, null, param);
            });
        });
        document.addEventListener('close-modal', event=>{
            $('#modalPut').modal('hide');
        });
    </script>
@endpush
