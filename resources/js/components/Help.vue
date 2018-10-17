<template>
    <tile :position="position" modifiers="overflow">
        <section class="help">
            <h1 class="help__title">HELP! LAST UPDATED</h1>
            <ul class="help__documents">
                <li v-for="documentItem in documents" class="help__documents">
                    <h2 class="help__title">{{ documentItem.title }}</h2>
                    <div class="help__description">{{documentItem.description}}</div>
                </li>
            </ul>
        </section>
    </tile>
</template>

<script>
import echo from '../mixins/echo';
import Tile from './atoms/Tile';
import saveState from 'vue-save-state';
import { relativeDate } from '../helpers';

export default {
    components: {
        Tile,
    },

    mixins: [echo, saveState],

    props: ['position'],

    data() {
        return {
            documents: [],
        };
    },

    methods: {
        relativeDate,

        getEventHandlers() {
            return {
                'Help.LastUpdatedDocuments': response => {
                    this.documents = response.documents;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: 'help',
            };
        },
    },
};
</script>
