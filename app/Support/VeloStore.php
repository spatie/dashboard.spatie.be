<?php

namespace App\Support;

use Spatie\Valuestore\Valuestore;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class VeloStore
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

        $this->valuestore = Valuestore::make("{$path}/velo.json");
    }

    public function setStations(array $stations): self
    {
        $this->valuestore->put('stations', $stations);

        return $this;
    }

    public function stations(): Collection
    {
        return collect($this->valuestore->get('stations'))
            ->map(fn (array $veloStationAttributes) => new VeloStation($veloStationAttributes));
    }
}
