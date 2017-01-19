import { upperFirst } from 'lodash';
import moment from 'moment';

export function formatNumber(value) {
    if (! value) {
        return 0;
    }

    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}

export function addClassModifiers(base, modifiers = []) {
    if (! Array.isArray(modifiers)) {
        modifiers = modifiers.split(' ');
    }

    modifiers = modifiers.map(modifier => `${base}--${modifier}`);

    return [base, ...modifiers];
}

export function relativeDate(value) {
    const date = moment(value);

    const diffInDays = date.diff(moment(), 'days');

    if (diffInDays < 7) {
        return date.calendar();
    }

    return upperFirst(date.fromNow());
}

export function relativeDateTime(value) {
    const date = moment(value);

    if (date.diff(moment(), 'days') > 1) {
        return `${date.diff(moment(), 'days')} days ago`;
    }

    if (date.diff(moment(), 'hours') > 24) {
        return "A day ago"
    }

    if (date.diff(moment(), 'hours') > 1) {
        return `${date.diff(moment(), 'hours')} hours ago`;
    }

    if (date.diff(moment(), 'minutes') > 59) {
        return 'An hour ago';
    }

    if (date.diff(moment(), 'seconds') > 119) {
        return `${date.diff(moment(), 'minutes')} minutes ago`;
    }

    if (date.diff(moment(), 'seconds') >= 60) {
        return 'A minute ago';
    }

    if (date.diff(moment(), 'seconds') > 10) {
        return `${date.diff(moment(), 'seconds')} seconds ago`;
    }
console.log(`${date.diff(moment(), 'seconds')} seconds ago`);
    return 'Just now';
}

export function diffInSeconds(otherMoment) {
    console.log(otherMoment);
    return moment().diff(otherMoment, 'seconds')
}
