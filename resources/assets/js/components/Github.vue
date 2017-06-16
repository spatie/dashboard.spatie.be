<template>
    <grid-area :position="position">
        <section class="statistics">
            <h1>GitHub</h1>
            <ul>
                <li class="statistic">
                    <span class="statistic__stars"></span>
                    <span class="statistic__count">{{ formatNumber(stars) }}</span>
                </li>
                <li class="statistic">
                    <span class="statistic__label">Issues</span>
                    <span class="statistic__count">{{ formatNumber(issues) }}</span>
                </li>
                <li class="statistic">
                    <h2 class="statistic__label">Pull Requests</h2>
                    <span class="statistic__count">{{ formatNumber(pullRequests) }}</span>
                </li>
                <li class="statistic">
                    <h2 class="statistic__label">Contributors</h2>
                    <span class="statistic__count">{{ formatNumber(contributors) }}</span>
                </li>
                <li class="statistic">
                    <h2 class="statistic__label">Repos</h2>
                    <span class="statistic__count">{{ formatNumber(numberOfRepos) }}</span>
                </li>
            </ul>
        </section>
    </grid-area>
</template>

<script>
    import { formatNumber } from '../helpers';
    import echo from '../mixins/echo';
    import GridArea from './atoms/GridArea';
    import saveState from 'vue-save-state';

    export default {

        components: {
            GridArea,
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