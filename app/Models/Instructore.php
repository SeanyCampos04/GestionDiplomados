<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructore extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'cvu'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class);
    }

    public function solicitudInstructores()
    {
        return $this->hasMany(solicitud_instructore::class);
    }
    public function solicitudes()
    {
        return $this->belongsToMany(Diplomado::class, 'solicitud_instructores', 'instructore_id', 'diplomado_id');
    }
}
