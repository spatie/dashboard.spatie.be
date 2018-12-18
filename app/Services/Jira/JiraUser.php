<?php

namespace App\Services\Jira;

use JiraRestApi\JiraException;
use JiraRestApi\User\UserService;
use Spatie\Valuestore\Valuestore;

class JiraUser {
    private const ROOM_1502 = 28;
    private $jiraIssue;

    /**
     * Constructor
     */
    public function __construct(int $roomId = self::ROOM_1502)
    {
        $this->valuestore = Valuestore::make(storage_path("app/jira_$roomId.json"));
        $this->jiraIssue = new JiraIssue();
    }

    /**
     * @param string $trac
     * @return array
     */
    public function fetch($trac, $nocache = false)
    {
        if (!$nocache) {
            return $this->fromCache($trac);
        }

        try {
            $us = new UserService();
            $user = $us->get(['username' => $trac]);

            $issues = $this->jiraIssue->fetchByUser($trac);
            $user->issues = $issues;

            $user = $this->transform($user);
            $this->valuestore->put($trac, $user);

            return $user;
        } catch (JiraException $e) {
            print("Error Occured! " . $e->getMessage());
        }

        return [];
    }

    /**
     * @return 
     */
    public function fetchMany(array $tracs, $nocache = false)
    {
        $arr = [];

        foreach($tracs as $trac) {
            $arr[$trac] = $this->fetch($trac, $nocache);
        }

        return $arr;
    }

    /**
     * @param string $trac
     * @return array
     */
    private function fromCache($trac)
    {
        return $this->valuestore->get($trac, []);
    }

    /**
     * @return array
     */
    private function transform($user)
    {
        $data = [
            'key' => $user->key,
            'email' => $user->emailAddress,
            'avatar' => ((array) $user->avatarUrls)['48x48'],
            'fullname' => $user->displayName,
        ];

        $data['issues'] = isset($user->issues) ? $user->issues : [];

        return $data;
    }
}