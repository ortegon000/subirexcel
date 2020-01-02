<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imei extends Model
{
    protected $table = 'tblcodigos';

    protected $fillable = [
        'marca', 'modelo', 'imei'
    ];

    public $timestamps = false;
}
