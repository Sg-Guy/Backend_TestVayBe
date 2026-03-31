<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationModel extends Model
{
    
    //

    protected $table = 'applications'; // Spécifie le nom de la table associée à ce modèle

    protected $fillable = [ //  Les champs qui peuvent être assignés.
        'name',
        'email',
        'role',
        'motivation',
        'portfolio',
        'cv',

    ];
}
