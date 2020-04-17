<?php

namespace App\Tiles\TrainConnections;

use Illuminate\Support\Facades\File;
use Spatie\Valuestore\Valuestore;

class TrainConnectionsStore
{
    private Valuestore $valuestore;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $path = storage_path("app/dashboard");

        File::makeDirectory($path, 0755, true, true);

        $this->valuestore = Valuestore::make("{$path}/trains.json");
    }

    public function setTrainConnections(array $trains): self
    {
        $this->valuestore->put('trainConnections', $trains);

        return $this;
    }

    public function trainConnections(): array
    {
        return $this->valuestore->get('trainConnections');
    }
}
