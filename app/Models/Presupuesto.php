<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    use HasFactory;

    public function planilla()
    {
        return $this->hasMany('App\Models\Planilla','id','id');
    }
}
