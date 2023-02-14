<div>
    @include('nabre-quickadmin::livewire.form-manage.' . $view . '.index')
</div>
@push('scripts')
    <script type="text/javascript">
        // waiting for DOM loaded
        document.addEventListener('DOMContentLoaded', function() {
            // listen for the event
            window.livewire.on('urlChange', param => {
                // pushing on the history by passing the current url with the param appended
                history.pushState(null, null, param);
            });
        });
    </script>
@endpush
