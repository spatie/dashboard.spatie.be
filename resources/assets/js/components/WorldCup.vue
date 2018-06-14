<template>
    <tile :position="position" modifiers="overflow">
        <section class="worldcup">
            <h1>World Cup</h1>
            <ul>
                <li v-for="match in matches" class="match">
                    <span class="match__home">{{ match.home_team.code }}</span>
                    <span v-if="match.status === 'future'" class="match__goals">{{ formatUntil(match.datetime) }}</span>
                    <span v-else class="match__goals">{{ match.home_team.goals }} - {{ match.away_team.goals }}</span>
                    <span class="match__out">{{ match.away_team.code }}</span>
                </li>
            </ul>
        </section>
    </tile>
</template>

<script>
    import Tile from './atoms/Tile';
    import { formatUntil } from '../helpers';
    import worldcup from '../services/worldcup/WorldCup';

    export default {

        components: {
            Tile,
        },

        props: {
            position: {
                type: String,
            },
        },

        data() {
            return {
                matches: [],
            };
        },

        created() {

            this.fetchTodaysMatches();
            setInterval(this.fetchTodaysMatches, 5 * 60 * 1000);
        },

        methods: {

            formatUntil,

            async fetchTodaysMatches() {
                this.matches = await worldcup.todaysMatches();
            },
        },
    };
</script>
