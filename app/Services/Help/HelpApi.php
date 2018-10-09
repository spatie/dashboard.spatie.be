<?php

namespace App\Services\Help;

class HelpApi
{
    private $base_url = 'http://help.tektonlabs.com/api';

    public function getRecentlyEdited()
    {
        $token = config('help.access_token');

        try {
            $response = json_decode(file_get_contents("{$this->base_url}/documents.list?token={$token}"), true);
            $events = $response['data'] ?? [];
        } catch (\Exception $e) {
            $events = [];
        }

        return collect($events);
    }
}
