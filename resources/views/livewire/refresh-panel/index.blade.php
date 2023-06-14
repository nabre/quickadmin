<div>
    <a wire:click="button" class="btn btn-sm btn-dark">AVVIA - php artisan {{ $this->fn() }}</a>
    <ul class="list-group">
        @foreach ($artisan as $line)
            <li class="list-group-item">{{ $line }}</li>
        @endforeach
    </ul>
</div>
