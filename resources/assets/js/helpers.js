import { upperFirst } from 'lodash';
import moment from 'moment';

export const formatNumber = value => {

    if (! value) {
        return 0;
    }

    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
};

export const modifyClass = (modifiers, base) => {

    if (! modifiers) {
        return base;
    }

    modifiers = Array.isArray(modifiers) ? modifiers : modifiers.split(' ');
    modifiers = modifiers.map(modifier => `${base}--${modifier}`);

    return [base, ...modifiers];
};

export const gridFromTo = value => {

    const [from, to = from] = value.toLowerCase().split(':');

    return modifyClass([`from-${from}`, `to-${to}`], 'grid');
};

export const relativeDate = value => {
    const date = moment(value);

    const diffInDays = date.diff(moment(), 'days');

    if (diffInDays < 7) {
        return date.calendar();
    }

    return upperFirst(date.fromNow());
};
