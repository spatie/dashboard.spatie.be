<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LaravelCloudReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function testTheDatabaseCacheAndSessionTablesExist(): void
    {
        $this->assertTrue(Schema::hasColumns('cache', ['key', 'value', 'expiration']));
        $this->assertTrue(Schema::hasColumns('cache_locks', ['key', 'owner', 'expiration']));
        $this->assertTrue(Schema::hasColumns('sessions', ['id', 'user_id', 'ip_address', 'user_agent', 'payload', 'last_activity']));
    }

    public function testTheDatabaseCacheStoreCanStoreAndLockValues(): void
    {
        $databaseCache = Cache::store('database');

        $databaseCache->put('greeting', ['hello' => 'world'], 60);

        $this->assertSame(['hello' => 'world'], $databaseCache->get('greeting'));
        $this->assertTrue($databaseCache->lock('task', 10)->get());
    }
}
