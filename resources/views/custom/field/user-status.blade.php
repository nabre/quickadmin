@if ($data instanceof App\Models\User)
    <ul class="list-group small">
        <li class="list-group-item p-1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-2">
                        <x-boolean :data="data_get($data,'email_verified_at')" />
                    </div>
                    <div class="col">
                        Email confermata
                    </div>
                </div>
            </div>
        </li>
        <li class="list-group-item p-1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-2">
                        <x-boolean :data="data_get($data,'password')" />
                    </div>
                    <div class="col">
                        Password definita
                    </div>
                </div>
            </div>
        </li>
        <li class="list-group-item p-1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-2">
                        <x-boolean :data="data_get($data,'enabled')" />
                    </div>
                    <div class="col">
                        Abilitato
                    </div>
                </div>
            </div>
        </li>
    </ul>
@else
    <p>Stato utente non visualizzabile</p>
@endif
