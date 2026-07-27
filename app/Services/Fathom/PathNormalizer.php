<?php

namespace App\Services\Fathom;

class PathNormalizer
{
    public function normalize(string $path): string
    {
        $path = parse_url($path, PHP_URL_PATH) ?: $path;
        $segments = explode('/', $path);

        $segments = array_map(function (string $segment): string {
            if ($this->isDynamicSegment($segment)) {
                return ':id';
            }

            return $segment;
        }, $segments);

        $normalized = implode('/', $segments);

        if ($normalized === '') {
            return '/';
        }

        return str_starts_with($path, '/') ? $normalized : ltrim($normalized, '/');
    }

    private function isDynamicSegment(string $segment): bool
    {
        if (preg_match('/^\d+$/', $segment) === 1) {
            return true;
        }

        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $segment) === 1) {
            return true;
        }

        if (preg_match('/^[0-9A-HJKMNP-TV-Z]{26}$/i', $segment) === 1) {
            return true;
        }

        return preg_match('/^[0-9a-f]{16,}$/i', $segment) === 1;
    }
}
