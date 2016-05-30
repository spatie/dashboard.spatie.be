import Grid from './grid';
import Pusher from '../mixins/pusher';
import SaveState from '../mixins/save-state';

export default {

    template: `
        <grid :position="grid" modifiers="transparent">
            <section :class="currentlyPlaying ? 'playing' : 'stopped' | modify-class 'last-fm'">
                        <div class="last-fm__content" v-if="currentlyPlaying">
                            <div class="last-fm__cover" v-if="hasCover" v-bind:style="{ backgroundImage: 'url(' + artwork + ')' }">
                            </div>
                            <div class="last-fm__text">
                                <div class="last-fm__artist">
                                    {{ artist }}
                                </div>
                                <div class="last-fm__track">
                                    {{ trackName }}
                                </div>
                                <span class="last-fm__user">
                                    {{ userName }}
                                </span>
                            </div>
                        </div>
                        <div class="last-fm__empty" v-else></div>
                        <div class="last-fm__background" v-if="hasCover" v-bind:style="{ backgroundImage: 'url(' + artwork + ')' }"></div>
            </section>
        </grid>
    `,

    components: {
        Grid,
    },

    mixins: [Pusher, SaveState],

    props: ['grid'],

    data() {
        return {
            artist: '',
            trackName: '',
            artwork: '',
            userName: '',
        };
    },

    computed: {
        currentlyPlaying() {
            return this.artist != '';
        },
        hasCover() {
            return this.artwork != '';
        },
    },

    methods: {
        getEventHandlers() {
            return {
                'App\\Components\\LastFm\\Events\\NothingPlaying': ()  => {
                    this.artist = '';
                },
                'App\\Components\\LastFm\\Events\\TrackIsPlaying': response => {
                    this.artist = response.trackInfo.artist;
                    this.trackName = response.trackInfo.trackName;
                    this.artwork = response.trackInfo.artwork;
                    this.userName = response.userName;
                },
            };
        },

        getSavedStateId() {
            return 'last-fm';
        },
    },
};