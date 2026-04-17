<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('oh_dear_messages', function (Blueprint $table) {
            $table->string('check_type')->nullable()->after('group_key');
        });
    }
};
