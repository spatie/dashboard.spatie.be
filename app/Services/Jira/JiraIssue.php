<?php

namespace App\Services\Jira;

use JiraRestApi\JiraException;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\Issue;

class JiraIssue {
    private $cached = [];
    private $issueService;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->issueService = new IssueService();
    }

    /**
     * @param string $key
     * @return array
     */
    public function fetchOne($key) {
        if (isset($this->cached[$key])) {
            return $this->cached[$key];
        }

        try {
            $queryParam = [
                'id',
                'key',
                'fields' => [  // default: '*all'
                    'summary',
                    'status' => ['name'],
                    'labels',
                    'customFields',
                ],
            ];
                    
            $issue = $this->issueService->get($key, $queryParam);
            
            if ($issue) {
                $issue = $this->transform($issue);
                $this->cached[$issue['key']] = $issue;

                return $issue;
            }
        } catch (JiraException $e) {
            print("Error Occured! " . $e->getMessage());
        }
    }

    /**
     * @param string $trac
     * @return array
     */
    public function fetchByUser($trac, $limit = 0) 
    {
        $jql = 'project not in (TEST)  and assignee = "'.$trac.'" and status in ("Ready for Dev", "In Progress", "Waiting for review", "Ready for testing", "Testing") order by updated DESC';

        try {
            $result = $this->issueService->search($jql);

            if ($result) {
                return $this->transformBulk($result->issues);
            }
        } 
        catch (JiraException $e) {
            print("Error Occured! " . $e->getMessage());
        }

        return [];
    }

    /**
     * @param string $key
     * @return string
     */
    public function getEpicName($key) 
    {
        if (isset($this->cached[$key])) {
            return $this->cached[$key]['summary'];
        }

        $issue = $this->issueService->get($key, [
            'fields' => [
                'summary'
            ]
        ]);

        if ($issue) {
            $this->cached[$key] = [
                'key' => $key,
                'summary' => $issue->fields->summary
            ];
            return $issue->fields->summary;
        }

        return '';        
    }

    /**
     * @return array
     */
    private function transform(Issue $issue) 
    {
        $data = [
            'id' => $issue->id,
            'key' => $issue->key,
            'summary' => $issue->fields->summary,
            'status' => $issue->fields->status->name,
            'labels' => $issue->fields->labels,
        ];

        foreach($issue->fields->customFields as $key => $value) {
            if (is_string($value) && strpos($value, 'AM-') !== FALSE) {
                $data['epic'] = [
                    'id' => explode('_', $key)[1],
                    'key' => $value,
                    'name' => $this->getEpicName($value)
                ];
            }
        }

        if (isset($data['epic'])) {
            $data['label'] = $data['epic']['name'];
        }
        else {
            $data['label'] = count($data['labels']) ? $data['labels'][0] : '';
        }

        return $data;
    }

    /**
     * @return array
     */
    private function transformBulk(array $issues) 
    {
        $arr = [];

        foreach($issues as $issue) {
            $arr[] = $this->transform($issue);
        }

        return $arr;
    }
}