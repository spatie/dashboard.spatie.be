import moment from 'moment';

moment.updateLocale('en', {
    calendar: {
        lastDay: '[Yesterday]',
        sameDay: '[Today]',
        nextDay: '[Tomorrow]',
        lastWeek: '[last] dddd',
        nextWeek: 'dddd',
        sameElse: 'L',
    },
});

// Pusher is forcing us to globally register the library. Bug? Shouldn't be
// necessary, hopefully we can remove this at some point.
window.Pusher = require('pusher-js');
