<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class datos_generales extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'departamento_id',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo_alternativo',
        'fecha_nacimiento',
        'tel_institucional',
        'celular',
        'extension',
        'nivel_estudios',
        'puesto',
        'horas',
        'rfc',
        'curp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}
