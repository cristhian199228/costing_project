<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Hoyvoy\CrossDatabase\Eloquent\Model;
use AjCastro\Searchable\Searchable;

class Patient extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'pacientes';
    protected $primaryKey = 'idpacientes';
    protected $connection = 'covid';

    protected $searchable = [
        'columns' => [
            'pacientes.numero_documento',
            'pacientes.nombres',
            'pacientes.apellido_paterno',
            'pacientes.apellido_materno',
            'nombre_completo' => 'CONCAT(pacientes.nombres, " ", pacientes.apellido_paterno, " ", pacientes.apellido_materno )'
        ],
    ];

    public function tracing(){
        return $this->hasMany(Tracing::class, 'patient_id', 'idpacientes');
    }

    public function attention(){
        return $this->hasMany(Attention::class, 'patient_id', 'idpacientes');
    }
}
