<?php

namespace Tests\Unit\Services\Fathom;

use PHPUnit\Framework\TestCase;
use App\Services\Fathom\PathNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;

class PathNormalizerTest extends TestCase
{
    #[DataProvider('dynamicPaths')]
    public function testItNormalizesDynamicPathSegments(string $path, string $expected): void
    {
        $normalizer = new PathNormalizer();

        $this->assertSame($expected, $normalizer->normalize($path));
    }

    /** @return array<string, array{string, string}> */
    public static function dynamicPaths(): array
    {
        return [
            'numeric' => ['/users/123/invoices/456', '/users/:id/invoices/:id'],
            'uuid' => ['/errors/6ba7b810-9dad-11d1-80b4-00c04fd430c8', '/errors/:id'],
            'ulid' => ['/sends/01J0NZY9Z2ZB8JZH0F7G8WMN7K', '/sends/:id'],
            'long hexadecimal' => ['/traces/0123456789abcdef01234567', '/traces/:id'],
            'static slug' => ['/blog/2026-release', '/blog/2026-release'],
            'query string' => ['/users/123?tab=activity', '/users/:id'],
        ];
    }
}
