<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('now_playing_songs', function (Blueprint $table) {
            $table->unsignedBigInteger('source_song_id')->nullable();
            $table->string('playback_source')->default('owntone');
        });
    }

    public function down(): void
    {
        Schema::table('now_playing_songs', function (Blueprint $table) {
            $table->dropColumn(['source_song_id', 'playback_source']);
        });
    }
};
