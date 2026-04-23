<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('now_playing_songs', function (Blueprint $table) {
            $table->string('next_song_title')->nullable()->change();
            $table->string('next_song_artist')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('now_playing_songs', function (Blueprint $table) {
            $table->string('next_song_title')->nullable(false)->change();
            $table->string('next_song_artist')->nullable(false)->change();
        });
    }
};
