import Chart from 'chart.js';
import Echo from 'laravel-echo';
import moment from 'moment';
import Vue from 'vue';

import CurrentTime from './components/current-time';
import GithubFile from './components/github-file';
import GoogleCalendar from './components/google-calendar';
import InternetConnection from './components/internet-connection';
import LastFm from './components/last-fm';
import PackagistStatistics from './components/packagist-statistics';
import RainForecast from './components/rain-forecast';

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: window.dashboard.pusherKey,
});

moment.locale('en', {
    calendar: {
        lastDay: '[Yesterday]',
        sameDay: '[Today]',
        nextDay: '[Tomorrow]',
        lastWeek: '[last] dddd',
        nextWeek: 'dddd',
        sameElse: 'L',
    },
});

Chart.defaults.global.legend.display = false;
Chart.defaults.global.tooltips.enabled = false;

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
    },
});
