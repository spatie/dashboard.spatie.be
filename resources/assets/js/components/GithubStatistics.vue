<template>
    <grid :position="grid" modifiers="padded overflow blue">
        <section class="packagist-statistics">
            <h1>GitHub</h1>
            <ul>
                <li class="packagist-statistic">
                    <span class="packagist-statistics__period">Stars</span>
                    <span class="packagist-statistics__count">{{ formatNumber(stars) }}</span>
                </li>
                <li class="packagist-statistic">
                    <span class="packagist-statistics__period">Issues</span>
                    <span class="packagist-statistics__count">{{ formatNumber(issues) }}</span>
                </li>
                <li class="packagist-statistic">
                    <h2 class="packagist-statistics__period">Pull Requests</h2>
                    <span class="packagist-statistics__count">{{ formatNumber(pullRequests) }}</span>
                </li>
                <li class="packagist-statistic">
                    <h2 class="packagist-statistics__period">Contributors</h2>
                    <span class="packagist-statistics__count">{{ formatNumber(contributors) }}</span>
                </li>
                <li class="packagist-statistic">
                    <h2 class="packagist-statistics__period">Forks</h2>
                    <span class="packagist-statistics__count">{{ formatNumber(forks) }}</span>
                </li>

            </ul>
        </section>
    </grid>
</template>

<script>
import { formatNumber } from '../helpers';
import echo from '../mixins/echo';
import Grid from './Grid';
import saveState from 'vue-save-state';

export default {

    components: {
        Grid,
    },

    mixins: [echo, saveState],

    props: ['grid'],

    data() {
        return {
            stars: 0
            issues: 0,
            pullRequests: 0,
            contributors: 0,
            forks: 0
        };
    },

    methods: {
        formatNumber,

        getEventHandlers() {
            return {
                'GitHub.StatisticsFetched': response => {
                    this.stars = response.stars;
                    this.issues = response.issues;
                    this.pullRequests = response.pullRequests;
                    this.contributors = response.contributors;
                    this.forks = response.forks;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: `github-statistics`,
            };
        },
    },
};
</script>