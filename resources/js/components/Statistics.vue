<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup" style="grid-template-rows: 1.5rem 1fr;">
            <h1>Packages</h1>
            <ul class="align-self-center">
                <li>
                    <span v-html="emoji('✨')" />
                    <span class="font-medium">{{ formatNumber(githubStars) }}</span>
                </li>
                <li>
                    <span>Contributors</span>
                    <span class="font-medium">{{ formatNumber(githubContributors) }}</span>
                </li>
                <li>
                    <span>Issues</span>
                    <span class="font-medium">{{ formatNumber(githubIssues) }}</span>
                </li>
                <li>
                    <span>Pull Requests</span>
                    <span class="font-medium">{{ formatNumber(githubPullRequests) }}</span>
                </li>
                <li>
                    <span>
                        <span class="mr-2">Downloads</span> <span class="text-dimmed">30 days</span>
                    </span>
                    <span class="font-medium">{{ formatNumber(packagistMonthly) }}</span>
                </li>
                <li>
                    <span>
                        <span class="opacity-0 mr-2">Downloads</span>
                        <span class="text-dimmed">Total</span>
                    </span>
                    <span class="font-medium">{{ formatNumber(packagistTotal) }}</span>
                </li>
            </ul>
        </div>
    </tile>
</template>

<script>
import { emoji, formatNumber } from '../helpers';
import echo from '../mixins/echo';
import Tile from './atoms/Tile';
import saveState from 'vue-save-state';

export default {
    components: {
        Tile,
    },

    mixins: [echo, saveState],

    props: ['position'],

    data() {
        return {
            githubStars: 0,
            githubIssues: 0,
            githubPullRequests: 0,
            githubContributors: 0,

            packagistTotal: 0,
            packagistMonthly: 0,
        };
    },

    methods: {
        emoji,
        formatNumber,

        getEventHandlers() {
            return {
                'Statistics.GitHubTotalsFetched': response => {
                    this.githubStars = response.githubStars;
                    this.githubIssues = response.githubIssues;
                    this.githubPullRequests = response.githubPullRequests;
                    this.githubContributors = response.githubContributors;
                },

                'Statistics.PackagistTotalsFetched': response => {
                    this.packagistTotal = response.total;
                    this.packagistMonthly = response.monthly;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: 'statistics',
            };
        },
    },
};
</script>
