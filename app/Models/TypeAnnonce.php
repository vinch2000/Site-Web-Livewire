<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAnnonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom'
    ];

    public function biens()
    {
        return $this->hasMany(Bien::class);
    }
}
