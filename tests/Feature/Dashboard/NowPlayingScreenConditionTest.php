<?php

namespace Tests\Feature\Dashboard;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Dashboard\Conditions\NowPlayingScreenCondition;

class NowPlayingScreenConditionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'screen-condition-testing');
        config()->set('database.connections.screen-condition-testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        DB::purge('screen-condition-testing');

        Schema::create('now_playing_songs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function testItDisplaysWhenASongWasUpdatedInTheLastTenMinutes(): void
    {
        DB::table('now_playing_songs')->insert([
            'created_at' => now()->subMinutes(5),
            'updated_at' => now()->subMinutes(5),
        ]);

        $this->assertTrue(app(NowPlayingScreenCondition::class)->shouldDisplay());
    }

    public function testItDoesNotDisplayWithoutARecentlyUpdatedSong(): void
    {
        DB::table('now_playing_songs')->insert([
            'created_at' => now()->subMinutes(11),
            'updated_at' => now()->subMinutes(11),
        ]);

        $this->assertFalse(app(NowPlayingScreenCondition::class)->shouldDisplay());
    }
}
