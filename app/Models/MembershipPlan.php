<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;
    
    // 💡 FIX: Tell the model to use the correct table name
    protected $table = 'memberships'; 
      protected $fillable = [
    'plan_id', 'name', 'price', 'description', 'duration_type', 'duration_days'
];


    // Assuming you don't use 'created_at' and 'updated_at' 
    // if this is a static plans table:
    // public $timestamps = false;
    
    // ... (rest of the class)
}