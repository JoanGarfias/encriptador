<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encriptados extends Model
{
    use HasFactory;
    protected $table = 'encriptados';
    protected $fillable = ['user_id', 'content', 'key', 'created_at'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
