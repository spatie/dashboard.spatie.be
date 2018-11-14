<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup" style="--template-rows: auto 1fr;">
            <h1>Statistics</h1>
            <ul class="align-self-center">
                <li>
                    <span>★</span>
                    <span class="font-medium text-accent">{{ formatNumber(githubStars) }}</span>
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
                    <span>Total (PHP)</span>
                    <span class="font-medium">{{ formatNumber(packagistTotal) }}</span>
                </li>
                <li>
                    <span>30 days (PHP)</span>
                    <span class="font-medium">{{ formatNumber(packagistMonthly) }}</span>
                </li>
                <li>
                    <span>Total (JS)</span>
                    <span class="font-medium">{{ formatNumber(npmTotal) }}</span>
                </li>
                <li>
                    <span>30 days (JS)</span>
                    <span class="font-medium">{{ formatNumber(npmMonthly) }}</span>
                </li>
            </ul>
        </div>
    </tile>
</template>

<script>
import { formatNumber } from '../helpers';
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

            npmTotal: 0,
            npmMonthly: 0,
        };
    },

    methods: {
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

                'Statistics.NpmTotalsFetched': response => {
                    this.npmTotal = response.total;
                    this.npmMonthly = response.monthly;
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
