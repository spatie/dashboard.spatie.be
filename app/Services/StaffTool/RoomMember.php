<?php

namespace App\Services\StaffTool;

use Spatie\Valuestore\Valuestore;
use GuzzleHttp\Client;
use Guzzle;
use function GuzzleHttp\Psr7\readline;


class RoomMember
{
    private const KEY_MEMBERS = 'members';
    private const KEY_ARCHIVED = 'archived_members';
    private const ROOM_1502 = 28;

    protected $valuestore;
    protected $roomId;

    public function __construct(int $roomId = self::ROOM_1502)
    {
        $this->valuestore = Valuestore::make(storage_path("app/room_member_$roomId.json"));
        $this->roomId = $roomId;
    }

    /**
     * @return array
     */
    public static function all(): array
    {
        //return (new static())->getMembers();
        return (new static())->getDumb();
    }

    /**
     * @return void
     */
    public function resync()
    {
        // $members = $this->valuestore->get('members', []);
        $members = $this->parse();
        $archived = [];
        $this->valuestore->put(self::KEY_MEMBERS, $members);
        $this->valuestore->put(self::KEY_ARCHIVED, $archived);
    }

    /**
     * Exclude a person from member list
     * @param string $trac
     */
    public function archive(string $trac) {
        $members = $this->getMembers();
        $archived = $this->getArchived();

        $i = array_search($trac, array_column($members, 'trac'));
        if ($i === FALSE) {
            return;
        }

        $member = $members[$i];
        unset($members[$i]);

        $k = array_search($trac, array_column($archived, 'trac'));
        if ($k !== FALSE) {
            unset($archived[$k]);
        }

        $archived[] = $member;

        $this->valuestore->put(self::KEY_MEMBERS, array_values($members));
        $this->valuestore->put(self::KEY_ARCHIVED, array_values($archived));
    }

    /**
     * @return array
     */
    public function getMembers(): array
    {
        return $this->valuestore->get(self::KEY_MEMBERS, []);
    }

    /**
     * @return array
     */
    public function getArchived(): array
    {
        return $this->valuestore->get(self::KEY_ARCHIVED, []);
    }

    /**
     * Crawl members from staff tool
     */
    private function parse() {
        //external network
        return $this->getDumb();

        //internal network
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
                'isDev' => in_array($trac, $this->devs)
            ];
        }

        return $members;
    }

    /**
     * Static data for temporary use
     */
    public function getDumb(){
        return [
            ['trac'=>'liembt','name'=>'Bùi Thanh Liêm','gender'=>'male', 'isDev'=>1],
            ['trac'=>'sondt','name'=>'Đào Thanh Sơn','gender'=>'male', 'isDev'=>1],
            ['trac'=>'kimhong','name'=>'Trần Thị Kim Hồng','gender'=>'female', 'isDev'=>1],
            ['trac'=>'thuhta','name'=>'Hoàng Thị Anh Thư','gender'=>'female', 'isDev'=>1],
            ['trac'=>'phuongdm','name'=>'Dương Minh Phương','gender'=>'male', 'isDev'=>1],
            ['trac'=>'trungkien','name'=>'Nguyễn Trung Kiên','gender'=>'male', 'isDev'=>1],
            ['trac'=>'duy.maianh','name'=>'Mai Anh Duy','gender'=>'male', 'isDev'=>1],
            ['trac'=>'dunghv','name'=>'Hoàng Việt Dũng','gender'=>'male', 'isDev'=>1],
            ['trac'=>'sonvq','name'=>'Vũ Quang Sơn','gender'=>'male', 'isDev'=>1],
            ['trac'=>'anh','name'=>'Nguyễn Thị Vân Anh','gender'=>'female', 'isDev'=>0],
            ['trac'=>'quypv1','name'=>'Phạm Văn Quý','gender'=>'male', 'isDev'=>1],
            ['trac'=>'ductrinh','name'=>'Trịnh Minh Đức','gender'=>'male', 'isDev'=>1],
            ['trac'=>'tanustark','name'=>'Nguyễn Minh Tuấn','gender'=>'male', 'isDev'=>0],
            ['trac'=>'cuongnh','name'=>'Nguyễn Huy Cương','gender'=>'male', 'isDev'=>1],
            ['trac'=>'vunguyen','name'=>'Nguyễn Hải Vũ','gender'=>'male', 'isDev'=>0],
            ['trac'=>'vanquyet','name'=>'Trần Văn Quyết','gender'=>'male', 'isDev'=>1],
        ];
    }
}
