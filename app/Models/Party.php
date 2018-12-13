<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ARCHIVED = 'archived';

    protected $table = 'parties';
    
    protected $guarded = [];

    protected $hidden = [];

    public function scopeActive($query) {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
