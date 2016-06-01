<?php

namespace App\Components\GitLab;

use App\Components\GitLab\Events\MergeRequestsFetched;
use Vinkla\GitLab\Facades\GitLab;
use Illuminate\Console\Command;

class FetchGitLabMergeRequests extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:gitlab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Gitlab file content.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //TODO: use a user account so we can get 'all' merge requests
        $projects = GitLab::api('projects')->owned();

        $mergeRequests = collect($projects)
            ->flatMap(function ($project) {
                //TODO: Use 'opened' once we have all merge requests
                return GitLab::api('merge_requests')->all($project['id']);
            })->reject(function ($mergeRequest) {
                return is_null($mergeRequest['assignee']);
            })->groupBy(function ($mergeRequest) {
                return $mergeRequest['assignee']['username'];
            })->map(function ($user, $key) {
                return [
                    'name' => $key,
                    'count' => collect($user)->count()
                ];
            })
            ->sortByDesc(function ($user) {
                return $user['count'];
            })
            ->values()
            ->toArray();

        event(new MergeRequestsFetched($mergeRequests));
    }
}