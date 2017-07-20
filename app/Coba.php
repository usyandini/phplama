<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coba extends Model
{
    protected $connection = 'sqlsrv2';
    protected $table = 'PIL_DepositoProduct';
}
