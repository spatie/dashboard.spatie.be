<x-tile :position="$position">
    <div wire:poll.5s class="grid gap-padding h-full markup">
        <ul class="align-self-center">
            <li>
                <span>✨</span>
                <span class="font-bold variant-tabular">{{ formatNaturalNumber($gitHubStars) }}</span>
            </li>
            <li>
                <span>Contributors</span>
                <span class="font-bold variant-tabular">{{ formatNaturalNumber($gitHubContributors) }}</span>
            </li>
            <li>
                <span>Issues</span> <span class="font-bold variant-tabular">{{ formatNaturalNumber($gitHubIssues) }}</span>
            </li>
            <li>
                <span>Pull Requests</span>
                <span class="font-bold variant-tabular">{{ formatNaturalNumber($gitHubPullRequests) }}</span>
            </li>
            <li>
                <span>30 days</span>
                <span class="font-bold variant-tabular">{{ formatNaturalNumber($packagistMonthly) }}</span>
            </li>
            <li>
                <span>Total</span> <span class="font-bold variant-tabular">{{ formatNaturalNumber($packagistTotal) }}</span>
            </li>
        </ul>
    </div>
</x-tile>
