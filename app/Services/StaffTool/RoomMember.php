<?php

namespace App\Services\StaffTool;

// use App\Events\Twitter\Mentioned;
use Spatie\Valuestore\Valuestore;
use GuzzleHttp\Client;
use Guzzle;
use function GuzzleHttp\Psr7\readline;


class RoomMember
{
    private const KEY_MEMBERS = 'members';
    private const KEY_ARCHIVED = 'archived_members';

    /** @var \Spatie\Valuestore\Valuestore */
    protected $valuestore;
    /** @var int */
    protected $roomId;

    public function __construct(int $roomId)
    {
        $this->valuestore = Valuestore::make(storage_path("app/room_member_$roomId.json"));
        $this->roomId = $roomId;
    }

    public function resync()
    {
        // $members = $this->valuestore->get('members', []);
        $members = $this->parse();
        $archived = [];
        $this->valuestore->put(self::KEY_MEMBERS, $members);
        $this->valuestore->put(self::KEY_ARCHIVED, $archived);
    }

    public function archive(string $trac) {
        $members = $this->getMembers();
        $archived = $this->getArchived();

        $i = array_search($trac, array_column($members, 'trac'));
        $member = $members[$i];
        unset($members[$i]);

        $k = array_search($trac, array_column($archived, 'trac'));
        unset($archived[$k]);

        $archived[] = $member;

        $this->valuestore->put(self::KEY_MEMBERS, $members);
        $this->valuestore->put(self::KEY_ARCHIVED, $archived);
    }

    public function getMembers(): array
    {
        return $this->valuestore->get(self::KEY_MEMBERS, []);
    }

    public function getArchived(): array
    {
        return $this->valuestore->get(self::KEY_ARCHIVED, []);
    }

    private function parse() {
        return $this->getDumb();

        //production
        $client = new Client();
        $res = $client->request(
            'GET', 
            'https://stafftool.coccoc.com/user/roomlist/?room='.$this->roomId.'&status=1'
        );
        $body = $res->getBody();
        
        $members = [];
        $index = 0;
        while(!$body->eof()) {
            $index++;
            $line = readline($body);

            if ($index < 100) continue;
            if (!strpos($line, 'user/show/')) continue;

            // trac
            $start = strpos($line, '">');
            $end   = strpos($line, "</td>", $start);
            $trac  = substr($line, $start+2, $end-$start-2);

            // name
            $nextLine = readline($body);
            $start = strpos($nextLine, '>');
            $end   = strpos($nextLine, "</", $start);
            $name  = substr($nextLine, $start+1, $end-$start-1);

            $userId = (int) filter_var($line, FILTER_SANITIZE_NUMBER_INT);;
            $checkGender = $client->request(
                'GET',
                'https://stafftool.coccoc.com/user/show/'.$userId
            )
            ->getBody()
            ->getContents();

            $isMale = strpos($checkGender, 'Female') === FALSE;

            $members[] = [
                'trac' => $trac,
                'name' => $name,
                'gender' => $isMale ? 'male' : 'female',
            ];
        }

        return $members;
    }

    private function getDumb(){
        return [
            ['trac'=>'liembt','name'=>'Bùi Liêm','gender'=>'male'],
            ['trac'=>'kimhong','name'=>'Kim Hồng','gender'=>'female'],
            ['trac'=>'thuhta','name'=>'Anh Thư','gender'=>'female'],
            ['trac'=>'phuongdm','name'=>'Minh Phương','gender'=>'male'],
            ['trac'=>'trungkien','name'=>'Trung Kiên','gender'=>'male'],
            ['trac'=>'duy.maianh','name'=>'Anh Duy','gender'=>'male'],
        ];
    }
}
