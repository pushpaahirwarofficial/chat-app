<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content', 'document_path', 'photo_path', 'audio_path', 'camera_path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
