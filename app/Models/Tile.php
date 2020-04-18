<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tile extends Model
{
    public $guarded = [];

    public $casts = [
        'data' => 'array'
    ];

    public static function firstOrCreateForName(string $name): self
    {
        return static::firstOrCreate(['name' => $name]);
    }

    public function putData($name, $value)
    {
        $currentData = $this->data;

        $currentData[$name] = $value;

        $this->update([
            'data' => $currentData,
        ]);

        return $this;
    }

    public function getData(string $name)
    {
        return $this->data[$name] ?? null;
    }
}
