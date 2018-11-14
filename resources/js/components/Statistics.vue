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
                    <span>githubContributors</span>
                    <span class="font-medium">{{ formatNumber(githubContributors) }}</span>
                </li>
                <li>
                    <span>githubIssues</span>
                    <span class="font-medium">{{ formatNumber(githubIssues) }}</span>
                </li>
                <li>
                    <span>Pull Requests</span>
                    <span class="font-medium">{{ formatNumber(githubPullRequests) }}</span>
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
