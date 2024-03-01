<?php

namespace App\Models;

use App\Models\Pays;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ville extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'pays_id'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }
}
