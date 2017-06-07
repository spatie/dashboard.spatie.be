<?php

namespace App\Console\Components\Tasks;

use App\Events\Tasks\TasksFetched;
use Illuminate\Console\Command;
use App\Services\GitHub\GitHubApi;

class FetchTasks extends Command
{
    protected $signature = 'dashboard:fetch-tasks';

    protected $description = 'Fetch team members tasks from GitHub';

    public function handle(GitHubApi $api)
    {
        $fileNames = explode(',', config('services.github.files'));

        $contentOfFiles = collect($fileNames)
            ->combine($fileNames)
            ->map(function ($fileName) use ($api) {
                return $api->fetchFileContent('spatie', 'tasks', "{$fileName}.md", 'master');
            })
            ->map(function ($fileInfo) {
                return file_get_contents($fileInfo['download_url']);
            })
            ->map(function ($markdownContent) {
                return markdownToHtml($markdownContent);
            })
            ->toArray();

        event(new TasksFetched($contentOfFiles));
    }
}
