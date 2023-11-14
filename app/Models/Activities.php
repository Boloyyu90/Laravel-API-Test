<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'activities';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'id_content',
        'accessed_at',
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function Content()
    {
        return $this->belongsTo(Content::class, 'id_content');
    }
}
