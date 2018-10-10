<template>
    <tile :position="position">
        <div class="grid h-full" style="--template-rows: auto 1fr; --gap: 1rem">
            <h1 class="tile-title">GitHub</h1>
            <ul class="align-self-center tile-stats">
                <li>
                    <span>★</span>
                    <span class="text-accent">{{ formatNumber(stars) }}</span>
                </li>
                <li>
                    <span>Contributors</span>
                    <span>{{ formatNumber(contributors) }}</span>
                </li>
                <li>
                    <span>Repos</span>
                    <span>{{ formatNumber(numberOfRepos) }}</span>
                </li>
                <li>
                    <span>Issues</span>
                    <span>{{ formatNumber(issues) }}</span>
                </li>
                <li>
                    <span>Pull Requests</span>
                    <span>{{ formatNumber(pullRequests) }}</span>
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
            stars: 0,
            issues: 0,
            pullRequests: 0,
            contributors: 0,
            numberOfRepos: 0,
        };
    },

    methods: {
        formatNumber,

        getEventHandlers() {
            return {
                'GitHub.TotalsFetched': response => {
                    this.stars = response.stars;
                    this.issues = response.issues;
                    this.pullRequests = response.pullRequests;
                    this.contributors = response.contributors;
                    this.numberOfRepos = response.numberOfRepos;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: 'github',
            };
        },
    },
};
</script>
