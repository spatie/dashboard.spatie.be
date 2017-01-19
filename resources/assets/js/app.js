import './bootstrap.js';

import Echo from 'laravel-echo';
import Vue from 'vue';

import CurrentTime from './components/CurrentTime';
import GithubFile from './components/GithubFile';
import GoogleCalendar from './components/GoogleCalendar';
import InternetConnection from './components/InternetConnection';
import LastFm from './components/LastFm';
import PackagistStatistics from './components/PackagistStatistics';
import RainForecast from './components/RainForecast';
import Twitter from './components/Twitter';

new Vue({

    el: '#dashboard',

    components: {
        CurrentTime,
        GithubFile,
        GoogleCalendar,
        InternetConnection,
        LastFm,
        PackagistStatistics,
        RainForecast,
        Twitter,
    },

    created() {
        this.echo = new Echo({
            broadcaster: 'pusher',
            key: window.dashboard.pusherKey,
        });
    },
});
