<?php

return [
    'api_key' => env('LAST_FM_API_KEY'),

    'users' => explode(',', env('LAST_FM_USERS'))

];