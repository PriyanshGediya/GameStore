<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * This is the whitelist for the columns you want to allow to be
     * filled using methods like Transaction::create().
     */
    protected $fillable = [
        'transaction_user_id',
        'transaction_game_id',
        'qty',
        'final_price',
        'transaction_date_time',
    ];

    /**
     * Since you are manually handling the 'transaction_date_time' column,
     * it's good practice to disable Laravel's automatic timestamps.
     */
    public $timestamps = false;


    // Your existing relationships are correct and should be kept.
    public function user(){
        return $this->belongsTo(User::class, 'transaction_user_id', 'id');
    }

    public function game(){
        return $this->belongsTo(Game::class, 'transaction_game_id', 'id');
    }
}
