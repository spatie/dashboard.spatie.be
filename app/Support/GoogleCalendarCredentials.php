<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class GoogleCalendarCredentials
{
    /**
     * Laravel Cloud has no persistent disk, so the service account credentials
     * come from an environment variable and are written to the path the
     * Google Calendar package expects.
     */
    public function writeFromEnvironment(): void
    {
        $encodedCredentials = config('google-calendar.service_account_credentials_base64');

        if (blank($encodedCredentials)) {
            return;
        }

        $path = config('google-calendar.auth_profiles.service_account.credentials_json');

        if (File::exists($path)) {
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        File::put($path, base64_decode($encodedCredentials));

        File::chmod($path, 0600);
    }
}
