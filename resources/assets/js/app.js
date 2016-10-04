import { formatNumber, relativeDate } from './helpers';
import Chart from 'chart.js';
import Dashboard from './dashboard';
import Echo from 'laravel-echo';
import moment from 'moment';
import Vue from 'vue';

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

Vue.filter('relative-date', relativeDate);
Vue.filter('format-number', formatNumber);

new Vue({
    el: '#dashboard',
    render(createElement) {
        return createElement(Dashboard);
    },
});
