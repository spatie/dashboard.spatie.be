<?php

namespace App\Services\StaffTool;

use Spatie\Valuestore\Valuestore;
use GuzzleHttp\Client;
use Guzzle;
use function GuzzleHttp\Psr7\readline;
use App\Services\StaffTool\RoomMember;

class LeaveRequest
{
    private const KEY_STORE = 'leave_grouped_by_date';
    private const ROOM_1502 = 28;
    protected $valuestore;
    protected $roomId;

    /**
     * Constructor
     */
    public function __construct(int $roomId = self::ROOM_1502)
    {
        $this->valuestore = Valuestore::make(storage_path("app/room_leave_$roomId.json"));
        $this->roomId = $roomId;
    }

    /**
     * @return array
     */
    public function fetchMany(array $users = [], $nocache = false)
    {
        if (!$nocache) {
            return $this->fromCache();
        }

        $arr = [];

        foreach($users as $trac => $name) {
            $arr = array_merge(
                $arr, 
                $this->fetch($name, $trac, $nocache)
            );
        }

        $this->valuestore->put(self::KEY_STORE, $arr);

        return $arr;
    }

    /**
     * @return array
     */
    public function fetch(string $name, string $trac) {
        echo "Reading $trac \n";
        
        $client = new Client();
        $res = $client->request(
            'GET', 
            'https://stafftool.coccoc.com/leaverequest/allrequests?department=2&request_for='.urlencode($name),
            Auth::header()
        );
        $body = $res->getBody();

        $requests = [];
        $index = 0;
        $max = 5;
        $now = (new \DateTime())->format('Y-m-d');
        while(!$body->eof()) {
            $index++;
            $line = readline($body);

            if ($index < 180) continue; //skip html begining
            if (!strpos($line, '"leave-time"')) continue;

            //date
            $nextLine = readline($body);
            $half = null;

            if (strpos($nextLine, 'morning')) $half = 'AM';
            if (strpos($nextLine, 'afternoon')) $half = 'PM';

            $date = trim(str_replace(
                ['&nbsp;', 'from', 'to', '(all day)', '(afternoon)', '(morning)'],
                '',
                strip_tags($nextLine)
            ));

            if ($date >= $now) {
                $requests = array_merge($requests, $this->parseDate($date, $half));
            }

            if (count($requests) >= $max) break;
        }

        return [
            $trac => $requests
        ]; 
    }

    /**
     * @return array
     */
    private function parseDate(string $strDate, string $half = null)
    {
        $arr = [];

        $range = explode('  ', $strDate);
        $start = $range[0];
        $end = $range[1] ?? null;

        if ($end) {
            $range = new \DatePeriod(
                new \DateTime($start),
                new \DateInterval('P1D'),
                new \DateTime($end . ' 23:59:59')
            );

            foreach($range as $date) {
                $arr[] = $date->format('Y-m-d');
            }
        }
        else {
            $arr[] = $strDate . ($half ? " $half" : "");
        }

        return $arr;
    }

    /**
     * @return array
     */
    private function fromCache()
    {
        return $this->valuestore->get(self::KEY_STORE, []);
    }

    /**
     * @return array
     */
    public static function all(): array
    {
        $members = RoomMember::all();
        return (new static())->fetchMany(
            array_combine(array_column($members, 'trac'), array_column($members, 'name'))
        );
    }
}
