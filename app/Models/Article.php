<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
        // Colonnes que tu autorises à être remplies
        use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'image', // avatar ou image
    ];
}
