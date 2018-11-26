<?php

namespace App\Console\Components\TeamMember;

use Parsedown;
use Illuminate\Console\Command;
use App\Services\GitHub\GitHubApi;
use App\Events\TeamMember\TasksFetched;

class FetchTasksCommand extends Command
{
    protected $signature = 'dashboard:fetch-tasks';

    protected $description = 'Fetch team members tasks from GitHub';

    public function handle(GitHubApi $gitHub)
    {
        $this->info('Fetching tasks from GitHub...');

        $fileNames = explode(',', config('services.github.files'));

        $contentOfFiles = collect($fileNames)
            ->combine($fileNames)
            ->map(function ($fileName) use ($gitHub) {
                return $gitHub->fetchFileContent('spatie', 'tasks', "{$fileName}.md", 'master');
            })
            ->map(function ($fileInfo) {
                return file_get_contents($fileInfo['download_url']);
            })
            ->map(function ($markdownContent) {
                return $this->markdownToHtml($markdownContent);
            })
            ->map(function ($htmlContent) {
                return $this->formatTasks($htmlContent);
            })
            ->toArray();

        event(new TasksFetched($contentOfFiles));

        $this->info('All done!');
    }

    protected function markdownToHtml(string $markdown): string
    {
        return (new Parsedown())->text($markdown);
    }

    protected function formatTasks(string $html): string
    {
        $html = str_replace('(', '<span class="ml-2 font-bold variant-tabular">', $html);
        $html = str_replace(')', '</span>', $html);

        return $html;
    }
}
