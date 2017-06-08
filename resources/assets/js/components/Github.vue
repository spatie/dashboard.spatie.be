<template>
    <grid :position="grid" modifiers="padded overflow">
        <section class="packagist">
            <h1>GitHub</h1>
            <ul>
                <li class="packagist-statistic">
                    <span class="packagist__stars"></span>
                    <span class="packagist__count">{{ formatNumber(stars) }}</span>
                </li>
                <li class="packagist-statistic">
                    <span class="packagist__period">Issues</span>
                    <span class="packagist__count">{{ formatNumber(issues) }}</span>
                </li>
                <li class="packagist-statistic">
                    <h2 class="packagist__period">Pull Requests</h2>
                    <span class="packagist__count">{{ formatNumber(pullRequests) }}</span>
                </li>
                <li class="packagist-statistic">
                    <h2 class="packagist__period">Contributors</h2>
                    <span class="packagist__count">{{ formatNumber(contributors) }}</span>
                </li>
                <li class="packagist-statistic">
                    <h2 class="packagist__period">Repos</h2>
                    <span class="packagist__count">{{ formatNumber(numberOfRepos) }}</span>
                </li>

            </ul>
        </section>
    </grid>
</template>

<script>
    import {formatNumber} from '../helpers';
    import echo from '../mixins/echo';
    import Grid from './atoms/Grid';
    import saveState from 'vue-save-state';

    export default {

        components: {
            Grid,
        },

        mixins: [echo, saveState],

        props: ['grid'],

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