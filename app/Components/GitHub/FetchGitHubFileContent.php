<?php

namespace App\Components\GitHub;

use App\Events\GitHub\FileContentFetched;
use GitHub;
use Illuminate\Console\Command;

class FetchGitHubFileContent extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:github';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch GitHub file content.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fileNames = explode(',', env('GITHUB_FILES'));

        $fileContent = collect($fileNames)
            ->combine($fileNames)
            ->map(function ($fileName) {
                return GitHub::repo()->contents()->show('spatie', 'tasks', "{$fileName}.md", 'master');
            })
            ->map(function ($fileInfo) {
                return file_get_contents($fileInfo['download_url']);
            })
            ->map(function ($markdownContent) {
                return markdownToHtml($markdownContent);
            })
            ->toArray();

        event(new FileContentFetched($fileContent));
    }
}
