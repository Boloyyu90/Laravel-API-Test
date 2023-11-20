<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //nama table, primary key, timestamps
    public $timestamps = false;
    protected $table = "subscriptions";
    protected $primarykey = "id";
    protected $fillable = [
        'id_user',
        'category', 
        'price', 
        'transaction_date'
    ];
    
    public function User()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    // Define relationships or additional methods if needed
}