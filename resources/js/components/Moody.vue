<template>
    <tile :position="position">
        <section class="moody__cover">
            <iframe :src="src" width="100%" height="100%" frameBorder="0"></iframe>
        </section>
    </tile>
</template>

<script>
import echo from '../mixins/echo';
import Tile from './atoms/Tile';
import { addClassModifiers } from '../helpers';
import saveState from 'vue-save-state';

export default {
    components: {
        Tile,
    },

    mixins: [echo, saveState],

    props: ['position', 'src'],

    data() {
        return {
            url: '',
        };
    },

    computed: {
        cover() {
            return this.artwork || '/images/moody__cover.jpg';
        },
    },

    methods: {

        getSaveStateConfig() {
            return {
                cacheKey: 'moody',
            };
        },
    },
};
</script>
