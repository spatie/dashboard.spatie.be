import _ from 'lodash';
import moment from 'moment';

const formatNumber = value => {

    if(!value) {
        return 0;
    }

    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
};

const modifyClass = (modifiers, base) => {

    if (!modifiers) {
        return base;
    }

    modifiers = Array.isArray(modifiers) ? modifiers : modifiers.split(' ');
    modifiers = modifiers.map(modifier => `${base}--${modifier}`);

    return [base, ...modifiers];
};

const gridFromTo = value => {

    const [ from, to = from ] = value.toLowerCase().split(':');

    return modifyClass([`from-${from}`, `to-${to}`], 'grid');
};

const relativeDate = value => {
    let date = moment(value);

    let diffInDays = date.diff(moment(), 'days');

    if (diffInDays < 7) {
        return date.calendar();
    }

    return _.upperFirst(date.fromNow());
};

export { formatNumber, gridFromTo, modifyClass, relativeDate };
