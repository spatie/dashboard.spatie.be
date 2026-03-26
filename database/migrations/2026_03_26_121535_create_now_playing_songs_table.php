<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('now_playing_songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('artist');
            $table->string('requested_by');
            $table->string('next_song_title');
            $table->string('next_song_artist');
            $table->string('album_art_url')->nullable();
            $table->timestamps();
        });
    }
};
