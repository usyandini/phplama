<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periode';

    protected $fillable = [
        'tahun',
        'triwulan',
        'tanggal',
        'sejak',
        'hingga',
    ];
}
