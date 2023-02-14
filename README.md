
# ***WORK IN PROGRESS***
Il pacchetto è ancora in fase di elaborazione!
# Indice dei contenuti
| § | Argomento |
| :--- | :--- |
|1.  |[Introduzione](#1-introduzione) |
|2.  |[Installazione](#2-installazione)|


# 1 Introduzione
Il presente pacchetto viene impiegato per impostare alcune funzionalità di per lo sviluppo di applicazioni basate sul framework Laravel.

Si prevede l'impiego di un database MongoDB.
# 2 Installazione
## 2.1 Framework Laravel
Installazione del framework secondo la [guida](https://laravel.com/docs).
```
composer create-project laravel/laravel example-app
```
## 2.2 Preparazione dei file 
## Modifica file
Editare il file

## Modifica file /routes/web.php
```php
<?php

use Illuminate\Support\Facades\Route;

```
Verificare che la chiamata "/" non sia definita.

## Modifica il file ***config/permission.php***
```php
<?php

return [

    'models' => [
        'permission' => App\Models\Permission::class,
        'role' => App\Models\Role::class,
    ],

    'collection_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
    ],

    'cache_expiration_time' => 60 * 24,

    'log_registration_exception' => true,

    'display_permission_in_exception' => false,
];
```

## Modifica il file ***config/app.php***
Aggiungi le lingue dell'applicazione selezionabili.
```php
    'available_locales' => [/*'English' => 'en', 'Deutsch' => 'de', 'Français' => 'fr',*/ 'Italiano' => 'it'],
```

## Database
Si utilizza un database ***MongoDB*** in riferimento al pacchetto [***jenssegers/laravel-mongodb***](https://github.com/jenssegers/laravel-mongodb).

Modifica il file ***config/database.php***:
aggiungi nelle *connections* il seguente codice.

```php
'mongodb' => [
    'driver' => 'mongodb',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', 27017),
    'database' => env('DB_DATABASE', 'homestead'),
    'username' => env('DB_USERNAME', 'homestead'),
    'password' => env('DB_PASSWORD', 'secret'),
    'options' => [
        'database' => env('DB_AUTHENTICATION_DATABASE', 'admin'), // required with Mongo 3+
    ],
],
```
modifica il file ***.env*** aggiungendo e compilando i seguenti parametri:
```
DB_CONNECTION=mongodb
DB_HOST= 
DB_DATABASE= 
DB_USERNAME= 
DB_PASSWORD=
DB_AUTHENTICATION_DATABASE=admin
```

## 2.3 Installa il presente pacchetto:
```bash
composer require nabre/quickadmin
```

## 2.4 Seeders
Popolare il databse con *collections* e *documents* di base richiamare il seguente comando:
```bash
php artisan db:seed --class=Nabre\Quickadmin\Database\Seeders\DatabaseSeeder 
```

## 2.5 NPM
Si utilizzano i seguenti pacchetti [NPM(Node Package Manager)](https://docs.npmjs.com/) da installare:
```bash
npm install @fortawesome/fontawesome-free
npm install bootstrap
npm install flag-icons
npm install jquery
npm install jquery-ui
npm install livewire-sortable
npm install @popperjs/core

```
Compilatore npm con mix<br>
Controllare il metodo definito e installare nel caso.
```bash
npm install laravel-mix --save-dev
```
modicare ***/package.json***
```json
"scripts": {
    "dev": "npm run development",
    "development": "mix",
    "watch": "mix watch",
    "watch-poll": "mix watch -- --watch-options-poll=1000",
    "hot": "mix watch --hot",
    "prod": "npm run production",
    "production": "mix --production"
}
```
Creare/modificare il seguente file:
***/webpack.mix.js***

```js
const mix = require('laravel-mix');

mix.js('vendor/nabre/core/resources/js/app.js', 'public/js')
   .sass('vendor/nabre/core/resources/sass/app.scss', 'public/css')
   .sourceMaps()
   .version();
```
Aggiornare i files ***public/js/app.js*** e ***public/css/app.css*** eseguendo il comando:
```bash
npm run dev
```
# 3 Funzionalità
## 3.1 Ambienti predefiniti
Il sistema di gestione prevede i seguenti ambienti di base con predefinte alcune funzionalità di gestione.

| Uri principale    | Descrizione                                                                           |
| ---               | ---                                                                                   |
| *user/*            | percorso dove si ritrova la gestione del proprio profilo dopo l'esecuzione del login. |
| *manage/*          | Definite le pagine per la gestione delle funzionalità operative dell'applicazione.    |
| *admin/*           | Pannello amministrativo dell'applicazione.                                            |

## 3.2 Account
L'account predefinito, dopo aver popolato per la prima volta il database (funzione artisan db:seed specifico), prevedere le seguenti credenziali:

| **Nome utente:**  | admin@account.test    |
| ---               | ---                   |
| **Password:**     | password              |

## 3.3 Ruoli & permessi
L'applicazione si basa sul pacchetto [***mostafamaklad/laravel-permission-mongodb***](https://guthub.com/mostafamaklad/laravel-permission-mongodb) per gestire i ruoli e permessi.<br>
Consultare la guida per comprendere come integrarlo nella propia applicazione.

Nella presente applicazione è stato integrato un un sistema di ruoli gerarchico in funzione di una priorità definita, dove chi ha un valore minore può accedere a ruoli con priorità di valore maggiore.

In modo predefinito l'applicazione i seguenti ruoli con le rispettive priorità:
| Priorità | Ruolo     | Descrizione |
| ---:     | :---       | :--- |
| 1]       | *builder* | Vincolante, l'applicazione cerca il presente ruolo per poter definire l'accessibilità a tutti gli ambienti possibili e è definito nelle `Route` |
| 2]       | *admin*   | Utilizzato nelle `Route` |
| 3]       | *manage*  | Utilizzato nelle `Route` |

Qualora nelle `Route::middleware()` vengono aggiunti ruoli o permessi, questi possono essere aggiornati automaticamente nel database utilizzando il seguente comando:
```bash
php artisan roles:update
```
## 3.4 Route
Il comando `Route::resource()` è stato modificato nel seguente aggiungendo pagine da generare automaticamente.

È stato implementato un sistema delle funzioni `only()` & `exept()`.

## 3.5 Settings

## 3.6 Form & List
Per la manipolazione dei Model è presente la seguente classe da estendere per creare il modulo personalizzato.<br>
Un esempio di come si presenta il form:
```php

<?php

use ... as Model;
use Nabre\Quickadmin\Repositories\Form\Form;

class SimpleForm extends Form{
     protected $model = Model::class;

     function build()
     {
     }

     function settings():array
     {
        return [];
     }

     function query($items)
     {
        return $items;
     }

     function submit($builder,$values):bool
     {
        return true;
     }
}

```

Per richiamare la compilazione del Form utilizzare:
```php

 SimpleForm::public(**id**);

```

### *function* settings
| array key | type    | descrizione |
| ---       | ---     | ---         |
| id        |         | Come il parametro inseribile nella funzione statica *public*, è possibile inserire un istanza Model, oppure la stringa della chiave del Modello    |
| idData    | ?string |  chiave del modello           |
| view      | string  |  sono ammessi i valiri *list* e *form*, la prima per visualizzare l'elenco dei risultati mentre il secondo per accedere al form di modifica           |
| back      |   bool  |  con l'attribuzione *false* l'applicazione prevede di utilizzare esclusivamente il Form, senza permettere di visualizzare la lista.          |
| crud      |   bool  |  Nella visualizzazione *list* vengono tolti tutti gli elementi di comando crea,modifica, elimina,...          |
| trashsoft |   bool  |  Qualora il modello prevede il **softDelete**, serve per visualizzare le istanze nel cestino.           |
| onlyRead  |   bool  |  Visualizza esclusivamente le informazioni senza la possibilità di modificare i contenuti           |

### *function* query
È possibile filtrare i risultati maniplando il collect()

### *function* submit
| parametro | type    | descrizione |
| ---       | ---               | ---         |
| $builder  | instanceof Form    |             |
| $values  | array    |             |

*return*
| bool |  descrizione |
| ---       | ---               |
| true  | qualora si vuole proseguire con il salvataggio predefinito            |
| false | qualora si vuole proseguire senza eseguire il salvataggio predefinito |

### *function* build
È la funzione dove inserire i campi del form.


### 3.6.1 Compilare la funzione **build**
```php

 $->add($variable,$output);

```
| variabile |  descrizione |
| ---       | ---               |
| $variable  | Nome della variabile con cui viene definito il valore. Questo nome si collega al MOdel definito  |
| $output | Si imposta il tipo di interfaccia da utilizzare per manipolare il valore **Field::class** |



# 4 Artisan
Il presente pacchetto prevede alcuni comandi artisan aggiuntivi per facilitare alcune oprazioni di gestione dell'applicazione.

| Comando           	| Descrizione                                                       	|
| -------------     	| -------------                                                     	|
| mongodb:dump      | Crea un fil di backup del database MongoDB impostato.             	    |
| mongodb:restore       | Ripristina l'ultimo file di backup presente nel DB MongoDB        	|
| roles:update      	| Aggiorna ruoli e permessi utilizzati nel middleware delle route   	|


