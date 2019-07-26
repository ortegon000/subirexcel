<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imei extends Model
{
    protected $table = 'tblcodigos';

    protected $fillable = [
        'MARCA', 'MODELO', 'IMEI'
    ];

    public $timestamps = false;
}
