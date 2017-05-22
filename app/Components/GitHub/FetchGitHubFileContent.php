<?php

namespace App\Components\GitHub;

use App\Events\GitHub\FileContentFetched;
use App\Services\GitHub\GitHubApi;
use GitHub;
use Illuminate\Console\Command;

class FetchGitHubFileContent extends Command
{
    protected $signature = 'dashboard:github-files';

    protected $description = 'Fetch GitHub file content.';

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

        event(new FileContentFetched($contentOfFiles));
    }
}
