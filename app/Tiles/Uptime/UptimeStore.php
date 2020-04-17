<?php

namespace App\Tiles\Uptime;

use Illuminate\Support\Facades\File;
use Spatie\Valuestore\Valuestore;

class UptimeStore
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

        $this->valuestore = Valuestore::make("{$path}/uptime.json");
    }

    public function markSiteAsDown(string $siteUrl): self
    {
        $this->valuestore->put($siteUrl);

        return $this;
    }

    public function markSiteAsUp(string $siteUrl): self
    {
        $this->valuestore->forget($siteUrl);

        return $this;
    }

    public function clearDownSites(): self
    {
        $this->valuestore->flush();

        return $this;
    }

    public function downSites(): array
    {
        return array_keys($this->valuestore->all());
    }
}
