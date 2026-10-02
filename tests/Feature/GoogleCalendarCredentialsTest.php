<?php

namespace Tests\Feature;

use App\Support\GoogleCalendarCredentials;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class GoogleCalendarCredentialsTest extends TestCase
{
    private string $credentialsPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->credentialsPath = storage_path('framework/testing/google-calendar/credentials.json');

        File::deleteDirectory(dirname($this->credentialsPath));

        config()->set('google-calendar.auth_profiles.service_account.credentials_json', $this->credentialsPath);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(dirname($this->credentialsPath));

        parent::tearDown();
    }

    public function testItWritesTheCredentialsFromTheEnvironment(): void
    {
        config()->set('google-calendar.service_account_credentials_base64', base64_encode('{"type":"service_account"}'));

        app(GoogleCalendarCredentials::class)->writeFromEnvironment();

        $this->assertSame('{"type":"service_account"}', File::get($this->credentialsPath));
        $this->assertSame('0600', substr(sprintf('%o', fileperms($this->credentialsPath)), -4));
    }

    public function testItKeepsAnExistingCredentialsFile(): void
    {
        File::ensureDirectoryExists(dirname($this->credentialsPath));
        File::put($this->credentialsPath, '{"existing":true}');

        config()->set('google-calendar.service_account_credentials_base64', base64_encode('{"type":"service_account"}'));

        app(GoogleCalendarCredentials::class)->writeFromEnvironment();

        $this->assertSame('{"existing":true}', File::get($this->credentialsPath));
    }

    public function testItDoesNothingWithoutCredentialsInTheEnvironment(): void
    {
        config()->set('google-calendar.service_account_credentials_base64', null);

        app(GoogleCalendarCredentials::class)->writeFromEnvironment();

        $this->assertFileDoesNotExist($this->credentialsPath);
    }
}
