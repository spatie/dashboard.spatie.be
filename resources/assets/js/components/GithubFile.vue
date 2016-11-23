<template>
    <grid :position="grid" modifiers="overflow padded blue">
        <section class="github-file">
            <h1 class="github-file__title">{{ fileName }}</h1>
            <div class="github-file__content" v-html="contents"></div>
        </section>
    </grid>
</template>

<script>
import echo from '../mixins/echo';
import Grid from './Grid';
import saveState from 'vue-save-state';

export default {

    components: {
        Grid,
    },

    mixins: [echo, saveState],

    props: ['fileName', 'grid'],

    data() {
        return {
            contents: '',
        };
    },

    methods: {
        getEventHandlers() {
            return {
                'GitHub.FileContentFetched': response => {
                    this.contents = response.fileContent[this.fileName];
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: `github-file-${this.fileName}`,
            };
        },
    },
};
</script>
