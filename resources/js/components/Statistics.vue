<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup" style="grid-template-rows: 1.5rem 1fr;">
            <h1>Packages</h1>
            <ul class="align-self-center">
                <li>
                    <span v-html="emoji('✨')" />
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
                    <div
                        class="grid gap-0 w-full items-center"
                        style="grid-template-columns: 3rem auto 1fr;"
                    >
                        <span> PHP </span> <span class="text-dimmed text-xs"> Total </span>
                        <span class="font-medium justify-self-end">
                            {{ formatNumber(packagistTotal) }}
                        </span>
                        <span class="text-dimmed text-xs" style="grid-column-start: 2">
                            Monthly
                        </span>
                        <span class="font-medium justify-self-end">
                            {{ formatNumber(packagistMonthly) }}
                        </span>
                    </div>
                </li>
                <li>
                    <div
                        class="grid gap-0 w-full items-center"
                        style="grid-template-columns: 3rem auto 1fr;"
                    >
                        <span> JS </span> <span class="text-dimmed text-xs"> Total </span>
                        <span class="font-medium justify-self-end">
                            {{ formatNumber(npmTotal) }}
                        </span>
                        <span class="text-dimmed text-xs" style="grid-column-start: 2">
                            Monthly
                        </span>
                        <span class="font-medium justify-self-end">
                            {{ formatNumber(npmMonthly) }}
                        </span>
                    </div>
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

            npmTotal: 0,
            npmMonthly: 0,
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
