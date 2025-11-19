<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Genre;

class Game extends Model
{
    use HasFactory;

    // Add all fields you want to update via mass assignment
    protected $fillable = [
        'game_name',
        'game_detail',
        'game_price',
        'game_image',
        'game_pegi_rating',
        'game_genre_id',
        'genre_id',
         'installer',
    ];
     public function genre()
    {
       return $this->belongsTo(Genre::class, 'game_genre_id'); // specify the foreign key
    }
}
