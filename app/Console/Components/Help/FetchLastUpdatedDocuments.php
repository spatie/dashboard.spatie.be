<?php

namespace App\Console\Components\Help;

use Carbon\Carbon;
use App\Services\Help\HelpApi;
use Illuminate\Console\Command;
use App\Events\Help\LastUpdatedDocuments;

class FetchLastUpdatedDocuments extends Command
{
    protected $signature = 'dashboard:fetch-last-documents';

    protected $description = 'Fetch last documents from help';

    public function handle(HelpApi $help)
    {
        $documents = $help->getRecentlyEdited()->map(function ($doc) {
            $action = $doc['updatedAt'] == $doc['publishedAt'] ? 'published' : 'modified';
            $time = Carbon::parse($doc['updatedAt'])->diffForHumans();

            return [
                'title' => $doc['title'],
                'description' => "{$doc['updatedBy']['name']} {$action} about {$time}",
            ];
        });

        event(new LastUpdatedDocuments($documents));
    }
}
