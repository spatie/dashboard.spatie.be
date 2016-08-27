import Echo from '../mixins/echo';
import Grid from './grid';
import SaveState from '../mixins/save-state';

export default {

    template: `
        <grid :position="grid" modifiers="overflow padded blue">
            <section class="github-file">
                <h1 class="github-file__title">{{ fileName | capitalize }}</h1>
                <div  class="github-file__content">
                    {{{ contents }}}
                </div>
            </section>
        </grid>
    `,

    components: {
        Grid,
    },

    mixins: [Echo, SaveState],

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
                    console.log('response',response);
                    this.contents = response.fileContent[this.fileName];
                },
            };
        },

        getSavedStateId() {
            return `github-file-${this.fileName}`;
        },
    },
};
