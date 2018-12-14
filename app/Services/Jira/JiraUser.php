<?php

namespace App\Services\Jira;

use JiraRestApi\JiraException;
use JiraRestApi\User\UserService;
use Spatie\Valuestore\Valuestore;

class JiraUser {
    private const ROOM_1502 = 28;
    private const SUFFIX_USER = '_user';
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
    public function fetch($trac)
    {
        try {
            if ($cache = $this->fromCache($trac)) {
                return $cache;
            }

            $us = new UserService();
            $user = $us->get(['username' => $trac]);

            $issues = $this->jiraIssue->fetchByUser($trac);
            $user->issues = $issues;

            $user = $this->transform($user);
            $this->valuestore->put($trac.self::SUFFIX_USER, $user);

            return $user;
        } catch (JiraException $e) {
            print("Error Occured! " . $e->getMessage());
        }

        return [];
    }

    /**
     * @return 
     */
    public function fetchMany(array $tracs)
    {
        $arr = [];

        foreach($tracs as $trac) {
            $arr[$trac] = $this->fetch($trac);
        }

        return $arr;
    }

    /**
     * @param string $trac
     * @return string|null
     */
    private function fromCache($trac)
    {
        $user = $this->valuestore->get($trac.self::SUFFIX_USER);

        return $user ?? null;
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