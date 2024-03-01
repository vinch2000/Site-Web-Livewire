<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ville;
use App\Models\TypeBien;
use App\Models\TypeAnnonce;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bien extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'adresse',
        'ville_id',
        'type_bien_id',
        'type_annonce_id',
        'prix',
        'surface',
        'nb_pieces',
        'photo',
        'user_id'
    ];

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function typeBien()
    {
        return $this->belongsTo(TypeBien::class);
    }

    public function typeAnnonce()
    {
        return $this->belongsTo(TypeAnnonce::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPostedByCurrentUser()
    {
        return $this->user_id === auth()->id();
    }

    public function getPhotoAttribute($value)
    {
        return $value ? asset('images/' . $value) : asset('images/default.jpg');
    }

    public function getPhotoPathAttribute()
    {
        return $this->attributes['photo'] ? $this->attributes['photo'] : 'default.jpg';
    }

    public function getTypeAnnonceHtmlAttribute()
    {
        $couleur = "red";
        $texteAnnonce = "Vendu/Loué";
        if(!$this->sold)
        {
            switch($this->typeAnnonce->id)
            {
                case 1:
                    $couleur = "green";
                break;
                case 2:
                    $couleur = "orange";
                break;
            }
            $texteAnnonce = $this->typeAnnonce->type_annonce;
        }

        return '<p style="color:'.$couleur.';font-weight:bold">'.$texteAnnonce.'</p>';
    }
}
