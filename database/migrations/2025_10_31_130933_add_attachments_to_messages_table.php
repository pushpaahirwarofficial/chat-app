<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Add new fields after 'content'
            $table->string('document_path')->nullable()->after('content');
            $table->string('photo_path')->nullable()->after('content');
            $table->string('camera_photo_path')->nullable()->after('content');
            $table->string('audio_path')->nullable()->after('content');
            $table->string('contact_name', 100)->nullable()->after('content');
            $table->string('contact_phone', 20)->nullable()->after('content');
            $table->string('poll_question')->nullable()->after('content');
            $table->json('poll_options')->nullable()->after('content');
            $table->string('event_title')->nullable()->after('content');
            $table->dateTime('event_date')->nullable()->after('content');
            $table->string('sticker_path')->nullable()->after('content');
            $table->json('catalog_item')->nullable()->after('content');
            $table->string('quick_reply_text')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn([
                'document_path',
                'photo_path',
                'camera_photo_path',
                'audio_path',
                'contact_name',
                'contact_phone',
                'poll_question',
                'poll_options',
                'event_title',
                'event_date',
                'sticker_path',
                'catalog_item',
                'quick_reply_text',
            ]);
        });
    }
};
