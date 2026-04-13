<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oh_dear_messages', function (Blueprint $table) {
            $table->id();
            $table->string('event_type');
            $table->string('severity');
            $table->string('group_key')->index();
            $table->string('title');
            $table->string('site')->nullable();
            $table->json('payload');
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
        });
    }
};
