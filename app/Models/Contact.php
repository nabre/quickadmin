<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Contact extends \Nabre\Quickadmin\Models\Contact{

  use SoftDeletes;
}
