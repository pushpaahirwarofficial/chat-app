<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // ✅ THIS IS THE FIX

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $user;
    public $content;
    public $photo_path;
    public $document_path;
    public $created_at;

    public function __construct($user_id, $user, $content, $photo_path, $document_path, $created_at)
    {
        $this->user_id = $user_id;
        $this->user = $user;
        $this->content = $content;
        $this->photo_path = $photo_path;
        $this->document_path = $document_path;
        $this->created_at = $created_at;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('chat');
    }
}
