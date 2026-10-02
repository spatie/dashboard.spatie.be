<?php

return [

    /*
     * Path to the json file containing the credentials.
     */
    'service_account_credentials_json' => storage_path('app/google-calendar/service-account-credentials.json'),

    /*
     * Base64 encoded contents of the credentials json file. When set, the file is
     * written to the path above if it does not exist yet (used on Laravel Cloud).
     */
    'service_account_credentials_base64' => env('GOOGLE_CALENDAR_CREDENTIALS_BASE64'),

    /*
     *  The id of the Google Calendar that will be used by default.
     */
    'calendar_id' => env('GOOGLE_CALENDAR_ID'),
];
