<x-app-layout>

@php 

$currentDate = null; 
@endphp

<!-- Alpine.js + emoji picker -->
<script src="//unpkg.com/alpinejs" defer></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>

<!-- Container with Alpine state -->
<div x-data="{ showEmoji: false }" x-cloak class="relative">

    <div class="container mx-auto mt-6 p-6 bg-white rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Chat Room / Group</h2>

        {{-- 💬 Chat Messages --}}
        <div id="messages" class="space-y-3 mb-6 p-3 bg-gray-50 rounded-xl max-h-[70vh] overflow-y-auto shadow-inner">
            @foreach($messages as $message)
                @php
                    $messageDate = \Carbon\Carbon::parse($message->created_at)->toDateString();
                @endphp

                @if ($currentDate !== $messageDate)
                    @php $currentDate = $messageDate; @endphp
                    <div class="text-center my-4">
                        <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-sm shadow">
                            {{ \Carbon\Carbon::parse($messageDate)->isToday() ? 'Today' : 
                            (\Carbon\Carbon::parse($messageDate)->isYesterday() ? 'Yesterday' : 
                            \Carbon\Carbon::parse($messageDate)->format('F j, Y')) }}
                        </span>
                    </div>
                @endif

                
                @if($message->user_id === Auth::id())
                    {{-- ✅ Right Side (Current User) --}}
                    <div class="flex justify-end items-end gap-2">
                        <div class="max-w-xs sm:max-w-md bg-blue-500 text-white rounded-2xl rounded-br-none px-4 py-2 shadow">
                            <div class="message-item mb-3">
                                <div class="">

                                    {{-- 🖼️ Photo / Camera Photo --}}
                                    @if($message->photo_path || $message->camera_photo_path)
                                        <div class="mt-2 mb-2">
                                        <img 
                                            src="{{ asset('storage/' . ($message->photo_path ?? $message->camera_photo_path)) }}" 
                                            alt="photo" 
                                            class="rounded-2xl max-w-[220px] max-h-[220px] object-cover border border-gray-200 shadow-sm"
                                        />

                                        </div>
                                    @endif

                                     {{-- 📝 Message text --}}
                                    @if($message->content)
                                        <div class="text-sm text-white-800 mb-2">
                                            {{ $message->content }}
                                        </div>
                                    @endif
                                                                       
                                    {{-- 📄 Document --}}
                                    @if($message->document_path)
                                    <div class="mt-2 bg-purple-50 border border-purple-200 rounded-xl p-3 shadow-sm hover:bg-purple-100 transition">
                                        <div class="flex items-center justify-between">
                                            <!-- Left: File info -->
                                            <div class="flex items-center gap-3">
                                                <!-- File icon -->
                                                <div class="bg-purple-200 text-purple-700 w-10 h-10 flex items-center justify-center rounded-lg shadow-sm">
                                                    <i class="fa-solid fa-file-alt text-xl"></i>
                                                </div>

                                                <!-- File name & info -->
                                                <div class="flex flex-col">
                                                    <a href="{{ asset('storage/' . $message->document_path) }}" 
                                                    target="_blank" 
                                                    class="text-sm font-medium text-purple-700 hover:text-purple-900 underline truncate max-w-[150px]">
                                                        {{ basename($message->document_path) }}
                                                    </a>
                                                    <span class="text-xs text-gray-500">Document File</span>
                                                </div>
                                            </div>

                                            <!-- Right: Download -->
                                            <a href="{{ asset('storage/' . $message->document_path) }}" 
                                            download 
                                            class="flex items-center gap-1 bg-purple-500 hover:bg-purple-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-sm transition">
                                                <i class="fa-solid fa-download"></i>
                                                <span>Download</span>
                                            </a>
                                        </div>

                                        <!-- Inline Preview (iframe) -->
                                        <div class="mt-3">
                                            <iframe 
                                                src="{{ asset('storage/' . $message->document_path) }}" 
                                                class="w-full h-64 rounded-lg border border-purple-200 shadow-inner"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                    </div>

                                    @endif

                                    {{-- 🎧 Audio --}}
                                    @if($message->audio_path)
                                        <div class="mt-2">
                                            <audio controls class="w-full rounded-lg">
                                                <source src="{{ asset('storage/' . $message->audio_path) }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                    @endif

                                    {{-- 👤 Contact --}}
                                    @if($message->contact_name || $message->contact_phone)
                                        <div class="mt-2 bg-sky-50 text-sky-800 p-2 rounded-xl text-sm">
                                            👤 <strong>{{ $message->contact_name }}</strong><br>
                                            📞 {{ $message->contact_phone }}
                                        </div>
                                    @endif

                                    {{-- 📊 Poll --}}
                                    @if($message->poll_question)
                                        <div class="mt-2 bg-yellow-50 text-yellow-800 p-2 rounded-xl">
                                            <div class="font-semibold">{{ $message->poll_question }}</div>
                                            @foreach(json_decode($message->poll_options ?? '[]') as $option)
                                                <div class="text-sm mt-1">🔘 {{ $option }}</div>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- 📅 Event --}}
                                    @if($message->event_title)
                                        <div class="mt-2 bg-pink-50 text-pink-800 p-2 rounded-xl text-sm">
                                            📅 <strong>{{ $message->event_title }}</strong><br>
                                            🕒 {{ $message->event_date ? \Carbon\Carbon::parse($message->event_date)->format('M d, Y h:i A') : '' }}
                                        </div>
                                    @endif

                                    {{-- 🌟 Sticker --}}
                                    @if($message->sticker_path)
                                        <div class="mt-2">
                                            <img 
                                                src="{{ asset('storage/' . $message->sticker_path) }}" 
                                                alt="sticker" 
                                                class="w-20 h-20 object-contain"
                                            >
                                        </div>
                                    @endif

                                    {{-- 🗂️ Catalog --}}
                                    @if($message->catalog_item)
                                        @php $catalog = json_decode($message->catalog_item, true); @endphp
                                        <div class="mt-2 bg-gray-100 text-gray-800 p-2 rounded-xl text-sm">
                                            🗂️ <strong>{{ $catalog['title'] ?? 'Catalog Item' }}</strong><br>
                                            {{ $catalog['description'] ?? '' }}
                                        </div>
                                    @endif

                                    {{-- ⚡ Quick Reply --}}
                                    @if($message->quick_reply_text)
                                        <div class="mt-2 bg-yellow-100 text-yellow-800 p-2 rounded-xl text-sm text-center">
                                            ⚡ {{ $message->quick_reply_text }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-[11px] text-blue-100 mt-1 text-right">
                                {{ $message->created_at->format('g:i A') }}
                            </div>
                        </div>

                        {{-- ✅ User Image (Current User) --}}
                        <img 
                            src="{{ $message->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=3b82f6&color=fff' }}"
                            alt="User Avatar"
                            class="w-8 h-8 rounded-full shadow object-cover">
                    </div>
                @else
                    {{-- ✅ Left Side (Other User) --}}
                    <div class="flex justify-start items-end gap-2">
                        {{-- ✅ User Image (Other User) --}}
                        <img 
                            src="{{ $message->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) . '&background=3b82f6&color=fff' }}"
                            alt="{{ $message->user->name }} Avatar"
                            class="w-8 h-8 rounded-full shadow object-cover">

                        <div class="max-w-xs sm:max-w-md bg-white text-gray-800 rounded-2xl rounded-bl-none px-4 py-2 shadow">
                            <div class="font-semibold text-xs mb-1 text-blue-600">
                                {{ $message->user->name }}
                            </div>
                            <div class="message-item mb-3">
                                <div class="">
                                    {{-- 🖼️ Photo / Camera Photo --}}
                                    @if($message->photo_path || $message->camera_photo_path)
                                        <div class="mt-2 mb-2">
                                            <img 
                                                src="{{ asset('storage/' . ($message->photo_path ?? $message->camera_photo_path)) }}" 
                                                alt="photo" 
                                                class="rounded-2xl max-w-[220px] max-h-[220px] object-cover border border-gray-200 shadow-sm"
                                            />
                                        </div>
                                    @endif

                                {{-- 📝 Message text --}}
                                    @if($message->content)
                                        <div class="text-sm text-white-800 mb-2">
                                            {{ $message->content }}
                                        </div>
                                    @endif

                                    {{-- 📄 Document --}}
                                    @if($message->document_path)
                                    <div class="mt-2 bg-purple-50 border border-purple-200 rounded-xl p-3 shadow-sm hover:bg-purple-100 transition">
                                        <div class="flex items-center justify-between">
                                            <!-- Left: File info -->
                                            <div class="flex items-center gap-3">
                                                <!-- File icon -->
                                                <div class="bg-purple-200 text-purple-700 w-10 h-10 flex items-center justify-center rounded-lg shadow-sm">
                                                    <i class="fa-solid fa-file-alt text-xl"></i>
                                                </div>

                                                <!-- File name & info -->
                                                <div class="flex flex-col">
                                                    <a href="{{ asset('storage/' . $message->document_path) }}" 
                                                    target="_blank" 
                                                    class="text-sm font-medium text-purple-700 hover:text-purple-900 underline truncate max-w-[150px]">
                                                        {{ basename($message->document_path) }}
                                                    </a>
                                                    <span class="text-xs text-gray-500">Document File</span>
                                                </div>
                                            </div>

                                            <!-- Right: Download -->
                                            <a href="{{ asset('storage/' . $message->document_path) }}" 
                                            download 
                                            class="flex items-center gap-1 bg-purple-500 hover:bg-purple-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-sm transition">
                                                <i class="fa-solid fa-download"></i>
                                                <span>Download</span>
                                            </a>
                                        </div>

                                        <!-- Inline Preview (iframe) -->
                                        <div class="mt-3">
                                            <iframe 
                                                src="{{ asset('storage/' . $message->document_path) }}" 
                                                class="w-full h-64 rounded-lg border border-purple-200 shadow-inner"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- 🎧 Audio --}}
                                    @if($message->audio_path)
                                        <div class="mt-2">
                                            <audio controls class="w-full rounded-lg">
                                                <source src="{{ asset('storage/' . $message->audio_path) }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                    @endif

                                    {{-- 👤 Contact --}}
                                    @if($message->contact_name || $message->contact_phone)
                                        <div class="mt-2 bg-sky-50 text-sky-800 p-2 rounded-xl text-sm">
                                            👤 <strong>{{ $message->contact_name }}</strong><br>
                                            📞 {{ $message->contact_phone }}
                                        </div>
                                    @endif

                                    {{-- 📊 Poll --}}
                                    @if($message->poll_question)
                                        <div class="mt-2 bg-yellow-50 text-yellow-800 p-2 rounded-xl">
                                            <div class="font-semibold">{{ $message->poll_question }}</div>
                                            @foreach(json_decode($message->poll_options ?? '[]') as $option)
                                                <div class="text-sm mt-1">🔘 {{ $option }}</div>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- 📅 Event --}}
                                    @if($message->event_title)
                                        <div class="mt-2 bg-pink-50 text-pink-800 p-2 rounded-xl text-sm">
                                            📅 <strong>{{ $message->event_title }}</strong><br>
                                            🕒 {{ $message->event_date ? \Carbon\Carbon::parse($message->event_date)->format('M d, Y h:i A') : '' }}
                                        </div>
                                    @endif

                                    {{-- 🌟 Sticker --}}
                                    @if($message->sticker_path)
                                        <div class="mt-2">
                                            <img 
                                                src="{{ asset('storage/' . $message->sticker_path) }}" 
                                                alt="sticker" 
                                                class="w-20 h-20 object-contain"
                                            >
                                        </div>
                                    @endif

                                    {{-- 🗂️ Catalog --}}
                                    @if($message->catalog_item)
                                        @php $catalog = json_decode($message->catalog_item, true); @endphp
                                        <div class="mt-2 bg-gray-100 text-gray-800 p-2 rounded-xl text-sm">
                                            🗂️ <strong>{{ $catalog['title'] ?? 'Catalog Item' }}</strong><br>
                                            {{ $catalog['description'] ?? '' }}
                                        </div>
                                    @endif

                                    {{-- ⚡ Quick Reply --}}
                                    @if($message->quick_reply_text)
                                        <div class="mt-2 bg-yellow-100 text-yellow-800 p-2 rounded-xl text-sm text-center">
                                            ⚡ {{ $message->quick_reply_text }}
                                        </div>
                                    @endif
                                </div>
                            </div>  
                            <div class="text-[11px] text-gray-500 mt-1 text-left">
                                {{ $message->created_at->format('g:i A') }}
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- ✉️ Message Input Form --}}

        <form 
            id="messageForm" 
            method="POST" 
            action="{{ url('/messages') }}" 
            enctype="multipart/form-data"
            class="flex items-center gap-2 mt-4 relative"
            x-data="{ open: false }"
        >
            @csrf

            <!-- Message Input -->
            <input 
                type="text" 
                id="messageInput" 
                x-ref="input"  
                name="content"
                placeholder="Type a message..." 
                class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                
            />

            <!-- Emoji Button (left) -->
            <button 
                type="button"
                @click="showEmoji = !showEmoji"
                class="bg-gray-100 hover:bg-blue-100 text-gray-600 hover:text-blue-600 rounded-full w-12 h-12 flex items-center justify-center shadow-sm transition"
            >
                <!-- using emoji for button icon is fine here -->
                😊
            </button>
                <!-- Emoji Picker Popup -->
            
            <!-- Mic Button -->
            <button 
                type="button"
                class="bg-blue-500 hover:bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-md transition duration-200 ease-in-out"
            >
                <!-- Microphone Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 1a3 3 0 013 3v8a3 3 0 01-6 0V4a3 3 0 013-3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 10v2a7 7 0 01-14 0v-2m7 9v4m0 0h-4m4 0h4" />
                </svg>
            </button>

            <!-- Attachment Icon -->
            <button 
                type="button"
                @click="open = !open"
                class="bg-blue-500 hover:bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-md transition duration-200 ease-in-out"
            >
                <!-- White Paperclip Icon (Heroicon) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.44 11.05L11.05 21.44a5 5 0 01-7.07-7.07l9.9-9.9a3.5 3.5 0 014.95 4.95l-9.9 9.9a2 2 0 01-2.83-2.83l9.19-9.19" />
                </svg>
            </button>

            <!-- Popup Menu -->
            <div 
                x-show="open" 
                @click.away="open = false"
                x-transition
                class="absolute right-16 bottom-14 bg-white shadow-2xl rounded-2xl p-4 border border-gray-100 w-80 z-50"
            >
                <div class="grid grid-cols-3 gap-4 text-center">

                    <!-- Document -->
                    <label class="flex flex-col items-center cursor-pointer hover:bg-gray-50 p-3 rounded-xl transition">
                        <div class="bg-purple-100 text-purple-600 w-10 h-10 flex items-center justify-center rounded-full mb-1">
                            📄
                        </div>
                        <span class="text-xs font-medium text-gray-700">Document</span>
                        <input type="file" id="document" name="document" accept=".pdf,.doc,.docx" class="hidden" />
                    </label>

                    <!-- Photos -->
                    <label class="flex flex-col items-center cursor-pointer hover:bg-gray-50 p-3 rounded-xl transition">
                        <div class="bg-blue-100 text-blue-600 w-10 h-10 flex items-center justify-center rounded-full mb-1">
                            🖼️
                        </div>
                        <span class="text-xs font-medium text-gray-700">Photos</span>
                        <input type="file" id="photo" name="photo" accept="image/*,video/*" class="hidden" />
                    </label>

                    <!-- Camera -->
                    <label class="flex flex-col items-center cursor-pointer hover:bg-gray-50 p-3 rounded-xl transition">
                        <div class="bg-pink-100 text-pink-600 w-10 h-10 flex items-center justify-center rounded-full mb-1">
                            📷
                        </div>
                        <span class="text-xs font-medium text-gray-700">Camera</span>
                        <input type="file" id="camera" name="camera" accept="image/*" capture="camera" class="hidden" />
                    </label>

                    <!-- Audio -->
                    <label class="flex flex-col items-center cursor-pointer hover:bg-gray-50 p-3 rounded-xl transition">
                        <div class="bg-orange-100 text-orange-600 w-10 h-10 flex items-center justify-center rounded-full mb-1">
                            🎧
                        </div>
                        <span class="text-xs font-medium text-gray-700">Audio</span>
                        <input type="file" id="audio" name="audio" accept="audio/*" class="hidden" />
                    </label>

                    <!-- Contact -->
                    <div class="flex flex-col items-center cursor-pointer hover:bg-gray-50 p-3 rounded-xl transition">
                        <div class="bg-sky-100 text-sky-600 w-10 h-10 flex items-center justify-center rounded-full mb-1">
                            👤
                        </div>
                        <span class="text-xs font-medium text-gray-700">Contact</span>
                    </div>

                    <!-- Poll -->
                    <div class="flex flex-col items-center cursor-pointer hover:bg-gray-50 p-3 rounded-xl transition">
                        <div class="bg-yellow-100 text-yellow-600 w-10 h-10 flex items-center justify-center rounded-full mb-1">
                            📊
                        </div>
                        <span class="text-xs font-medium text-gray-700">Poll</span>
                    </div>

                    <!-- Event -->
                    <div class="flex flex-col items-center cursor-pointer hover:bg-gray-50 p-3 rounded-xl transition">
                        <div class="bg-pink-100 text-pink-600 w-10 h-10 flex items-center justify-center rounded-full mb-1">
                            📅
                        </div>
                        <span class="text-xs font-medium text-gray-700">Event</span>
                    </div>

                    <!-- New Sticker -->
                    <div class="flex flex-col items-center cursor-pointer hover:bg-gray-50 p-3 rounded-xl transition">
                        <div class="bg-teal-100 text-teal-600 w-10 h-10 flex items-center justify-center rounded-full mb-1">
                            🌟
                        </div>
                        <span class="text-xs font-medium text-gray-700">Sticker</span>
                    </div>

                    <!-- Catalog -->
                    <div class="flex flex-col items-center cursor-pointer hover:bg-gray-50 p-3 rounded-xl transition">
                        <div class="bg-gray-200 text-gray-600 w-10 h-10 flex items-center justify-center rounded-full mb-1">
                            🗂️
                        </div>
                        <span class="text-xs font-medium text-gray-700">Catalog</span>
                    </div>

                    <!-- Quick Replies -->
                    <div class="flex flex-col items-center cursor-pointer hover:bg-gray-50 p-3 rounded-xl transition">
                        <div class="bg-yellow-200 text-yellow-700 w-10 h-10 flex items-center justify-center rounded-full mb-1">
                            ⚡
                        </div>
                        <span class="text-xs font-medium text-gray-700">Quick Replies</span>
                    </div>
                </div>
            </div>

        <!-- Send Button -->
        <button 
            type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-md transition duration-200 ease-in-out"
        >
            <!-- Paper Plane Icon (white, Heroicon style) -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10l18-7-7 18-2.5-7L3 10z" />
            </svg>
        </button>

    <!-- Emoji picker popup -->
    <div 
        x-show="showEmoji"
        @click.away="showEmoji = false"
        class="absolute bottom-12 left-0 bg-white border rounded-2xl shadow-lg w-72 z-50"
    >
        <emoji-picker
            @emoji-click="(e) => {
                if ($refs.input) {
                    $refs.input.value += e.detail.unicode;   // ✅ Append emoji
                    $nextTick(() => $refs.input.focus());     // ✅ Focus again
                }
            }"
        ></emoji-picker>
    </div>


        </form>

    </div>

<!-- small CSS to hide x-cloak elements until Alpine initializes -->
<style>
  [x-cloak] { display: none !important; }
</style>

    {{-- 🔥 Real-time Echo (Reverb) Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const currentUserId = {{ Auth::id() }};
        const messagesDiv = document.getElementById('messages');
        const form = document.getElementById('messageForm');
        const input = document.getElementById('messageInput');
        const photo = document.getElementById('photo');
        const documentFile = document.getElementById('document');
        const audio = document.getElementById('audio');
        const camera = document.getElementById('camera');

        messagesDiv.scrollTop = messagesDiv.scrollHeight;

        // Ask for notification permission first (only once)
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }
        
        // 🔥 Echo listener
        window.Echo.channel('chat').listen('MessageSent', (e) => {
            console.log('📡 Broadcast:', e);

            // Optionally trigger a browser notification
            if (Notification.permission === "granted") {
                new Notification(`New message from ${e.user}`, {
                    body: e.content,
                });
            }

            const isCurrentUser = e.user_id === currentUserId;
            const wrapper = document.createElement('div');
            wrapper.className = `flex items-end gap-2 ${isCurrentUser ? 'justify-end' : 'justify-start'} mt-2`;

            const avatar = `<img src="https://ui-avatars.com/api/?name=${encodeURIComponent(e.user)}&background=3b82f6&color=fff" class="w-8 h-8 rounded-full shadow" alt="${e.user}">`;

            const bubble = document.createElement('div');
            bubble.className = `max-w-xs sm:max-w-md px-4 py-2 rounded-2xl shadow ${
                isCurrentUser ? 'bg-blue-500 text-white rounded-br-none' : 'bg-white text-gray-800 rounded-bl-none'
            }`;

            if (!isCurrentUser) {
                const nameDiv = document.createElement('div');
                nameDiv.className = 'font-semibold text-xs mb-1 text-blue-600';
                nameDiv.textContent = e.user;
                bubble.appendChild(nameDiv);
            }

            if (e.content) {
                const msg = document.createElement('div');
                msg.className = 'text-sm mb-2';
                msg.textContent = e.content;
                bubble.appendChild(msg);
            }

            if (e.photo_path || e.camera_photo_path) {
                const img = document.createElement('img');
                img.src = `/storage/${e.photo_path || e.camera_photo_path}`;
                img.className = 'rounded-2xl max-w-[220px] max-h-[220px] object-cover border border-gray-200 mb-2';
                bubble.appendChild(img);
            }

            if (e.document_path) {
                const doc = document.createElement('a');
                doc.href = `/storage/${e.document_path}`;
                doc.target = '_blank';
                doc.textContent = '📄 View Document';
                doc.className = 'block text-sm underline text-purple-700 hover:text-purple-900 mb-2';
                bubble.appendChild(doc);
            }

            if (e.audio_path) {
                const aud = document.createElement('audio');
                aud.controls = true;
                aud.src = `/storage/${e.audio_path}`;
                aud.className = 'w-full mt-2 rounded-lg';
                bubble.appendChild(aud);
            }

            const time = document.createElement('div');
            time.className = `text-[11px] mt-1 ${isCurrentUser ? 'text-blue-100 text-right' : 'text-gray-500 text-left'}`;
            time.textContent = new Date(e.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            bubble.appendChild(time);

            wrapper.innerHTML = isCurrentUser
                ? `${bubble.outerHTML}${avatar}`
                : `${avatar}${bubble.outerHTML}`;

            messagesDiv.appendChild(wrapper);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        });

        // ✉️ Handle message send
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const content = input.value.trim();
            const hasFile = (photo.files.length || documentFile.files.length || audio.files.length || camera.files.length);

            if (!content && !hasFile) {
                alert('Please type a message or attach a file.');
                return;
            }

            const formData = new FormData(form);
            formData.append('content', content);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });

                const text = await response.text(); // ✅ Read as text first
                console.log('📦 Raw response:', text);

                let data;
                try {
                    data = JSON.parse(text);
                } catch (err) {
                    console.error('❌ Not JSON:', err);
                    alert('Server returned invalid JSON. Check Laravel logs.');
                    return;
                }

                console.log('✅ Parsed JSON:', data);
                input.value = '';
                photo.value = '';
                documentFile.value = '';
                audio.value = '';
                camera.value = '';

            } catch (err) {
                console.error('🚨 Network error:', err);
                alert('Network error. Check console or Laravel logs.');
            }
        });
    });
</script>

</x-app-layout>
