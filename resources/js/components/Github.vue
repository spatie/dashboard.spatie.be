<template>
    <tile :position="position" >
        <h1 class="tile-title">GitHub</h1>
        <ul class="tile-stats">
            <li>
                <span>★</span>
                <span>{{ formatNumber(stars) }}</span>
            </li>
            <li>
                <span>Issues</span>
                <span>{{ formatNumber(issues) }}</span>
            </li>
            <li>
                <span>Pull Requests</span>
                <span>{{ formatNumber(pullRequests) }}</span>
            </li>
            <li>
                <span>Contributors</span>
                <span>{{ formatNumber(contributors) }}</span>
            </li>
            <li>
                <span>Repos</span>
                <span>{{ formatNumber(numberOfRepos) }}</span>
            </li>
        </ul>
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
