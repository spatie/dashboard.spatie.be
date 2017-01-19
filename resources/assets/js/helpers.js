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

export function diffInSeconds(otherMoment) {
    console.log(otherMoment);
    return moment().diff(otherMoment, 'seconds')
}
