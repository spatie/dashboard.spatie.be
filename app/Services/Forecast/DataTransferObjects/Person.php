<?php

namespace App\Services\Forecast\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class Person extends DataTransferObject
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    public static function fromForecastAttributes(array $attributes): Person
    {
        return new Person([
            'id' => $attributes['id'],
            'name' => strtolower($attributes['first_name']),
        ]);
    }
}
