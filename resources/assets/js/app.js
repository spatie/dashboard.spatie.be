import './helpers/vue-filters';
import CurrentTime from './components/current-time';
import GithubFile from './components/github-file';
import GitlabMergeRequests from './components/gitlab-merge-requests';
import GoogleCalendar from './components/google-calendar';
import InternetConnection from './components/internet-connection';
import LastFm from './components/last-fm';
import moment from 'moment';
import PackagistStatistics from './components/packagist-statistics';
import RainForecast from './components/rain-forecast';
import Vue from 'vue';

moment.locale('en', {
    calendar: {
        lastDay : '[Yesterday]',
        sameDay : '[Today]',
        nextDay : '[Tomorrow]',
        lastWeek : '[last] dddd',
        nextWeek : 'dddd',
        sameElse : 'L',
    },
});

new Vue({

    el: 'body',

    components: {
        RainForecast,
        CurrentTime,
        GithubFile,
        GitlabMergeRequests,
        GoogleCalendar,
        InternetConnection,
        LastFm,
        PackagistStatistics,
    },

});


