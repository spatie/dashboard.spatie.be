<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup">
            <ul class="align-self-center">
                <li>
                    <span v-html="emoji('✨')" />
                    <span class="font-bold variant-tabular">{{ formatNumber(githubStars) }}</span>
                </li>
                <li>
                    <span>Contributors</span>
                    <span class="font-bold variant-tabular">{{ formatNumber(githubContributors) }}</span>
                </li>
                <li>
                    <span>Issues</span> <span class="font-bold variant-tabular">{{ formatNumber(githubIssues) }}</span>
                </li>
                <li>
                    <span>Pull Requests</span>
                    <span class="font-bold variant-tabular">{{ formatNumber(githubPullRequests) }}</span>
                </li>
                <li>
                    <span>30 days</span>
                    <span class="font-bold variant-tabular">{{ formatNumber(packagistMonthly) }}</span>
                </li>
                <li>
                    <span>Total</span> <span class="font-bold variant-tabular">{{ formatNumber(packagistTotal) }}</span>
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
                    this.githubStars = response.stars;
                    this.githubIssues = response.issues;
                    this.githubPullRequests = response.pullRequests;
                    this.githubContributors = response.contributors;
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
