<?php

namespace App\Services\StaffTool;

use Spatie\Valuestore\Valuestore;
use GuzzleHttp\Client;
use Guzzle;
use function GuzzleHttp\Psr7\readline;

class Auth
{
    private const KEY_STORE = 'session';
    protected $valuestore;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->valuestore = Valuestore::make(storage_path("app/staff_auth.json"));
    }

    /**
     * @return string
     */
    public function newSession()
    {
        $session = $this->getSession();
        $client = new Client(['cookies' => true]);
        $res = $client->request(
            'GET', 
            'https://stafftool.coccoc.com/',
            $this->getHeader($session)
        );
        $cookieJar = $client->getConfig('cookies')->toArray();
        $session = '';

        foreach($cookieJar as $item) {
            if ($item['Name'] === 'session') {
                $session = $item['Value'];
            }
        }

        $this->setSession($session);
        return $session;
    }

    /**
     * @return string
     */
    public function getSession(): string
    {
        return $this->valuestore->get(self::KEY_STORE, '') ?? env('STAFF_SESSION', '');
    }

    /**
     * @return void
     */
    private function setSession($session): void
    {
        $this->valuestore->put(self::KEY_STORE, $session);
    }

    /**
     * @return array
     */
    public function getHeader(string $session) {
        return [
            'headers' => [
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Connection' => 'keep-alive',
                'Cache-Contro' => 'max-age=0',
                'Cookie' => 'session='.$session,
                'Host' => 'stafftool.coccoc.com',
                'Upgrade-Insecure-Requests' => 1,
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36',
            ]
        ];
    }

    /**
     * @return array
     */
    public static function header(): array
    {
        $self = new static();
        return $self->getHeader($self->newSession());
    }
}
