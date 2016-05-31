import _ from 'lodash';
import moment from 'moment';
import Vue from 'vue';

Vue.filter('date', function (value) {
    let date = moment(value);

    let diffInDays = date.diff(moment(), 'days');

    if (diffInDays < 7) {
        return date.calendar();
    }

    return _.upperFirst(date.fromNow());
});

Vue.filter('number', (value) => {

    if(!value) {
        return 0;
    }

    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
});

Vue.filter('grid-from-to', (value) => {

    let cssClasses = value.split(':');

    if(cssClasses.length > 0) {
        cssClasses.push(cssClasses[0]); // add default end value
        cssClasses[0] = 'from-' + cssClasses[0].toLowerCase();
        cssClasses[1] = 'to-' + cssClasses[1].toLowerCase();
    }

    return cssClasses.slice(0,2).join(' ');
});

Vue.filter('modify-class', (value, base) => {

    if(!value) return base;

    let cssClasses = value.split(' ');
    cssClasses = cssClasses.map(cssClass => base + '--' + cssClass);
    cssClasses.unshift(base);

    return cssClasses.join(' ');
});
