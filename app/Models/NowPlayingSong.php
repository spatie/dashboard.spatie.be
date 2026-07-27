<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NowPlayingSong extends Model
{
    protected $guarded = [];

    public static function current(): ?self
    {
        return self::query()
            ->where('updated_at', '>=', now()->subMinutes(10))
            ->latest()
            ->first();
    }
}
