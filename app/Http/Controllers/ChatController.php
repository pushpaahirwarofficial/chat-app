<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->latest()->get()->reverse();
        return view('chat', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'content'   => 'nullable|string|max:2000',
            'document'  => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'photo'     => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:5120',
            'audio'     => 'nullable|file|mimes:mp3,wav,ogg|max:5120',
            'camera'    => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        // Prepare message data
        $data = [
            'user_id' => Auth::id(),
            'content' => $request->content,
        ];

        // 📄 Document Upload
        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')->store('uploads/documents', 'public');
        }

        // 🖼️ Photo/Video Upload
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('uploads/photos', 'public');
        }

        // 📷 Camera Upload
        if ($request->hasFile('camera')) {
            $data['camera_photo_path'] = $request->file('camera')->store('uploads/camera', 'public');
        }

        // 🎧 Audio Upload
        if ($request->hasFile('audio')) {
            $data['audio_path'] = $request->file('audio')->store('uploads/audio', 'public');
        }

        // 💾 Create Message
        $message = Message::create($data);

        // 📢 Fire broadcast event (same as before)
        broadcast(new MessageSent(
            Auth::user()->id,
            Auth::user()->name,
            $message->content,
            $message->photo_path,
            $message->document_path,
            $message->created_at,
        ))->toOthers();

        return response()->json([
            'status'  => 'success',
            'message' => [
                'id'                => $message->id,
                'content'           => $message->content,
                'photo_path'        => $message->photo_path ? asset('storage/' . $message->photo_path) : null,
                'document_path'     => $message->document_path ? asset('storage/' . $message->document_path) : null,
                'camera_photo_path' => $message->camera_photo_path ? asset('storage/' . $message->camera_photo_path) : null,
                'audio_path'        => $message->audio_path ? asset('storage/' . $message->audio_path) : null,
                'user_id'           => $message->user_id,
                'created_at'        => $message->created_at,
            ]
        ]);

    }


}

