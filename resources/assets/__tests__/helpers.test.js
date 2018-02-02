import { assert } from 'chai';
import { relativeDateTime, relativeDate, formatDuration } from '../js/helpers';
import moment from 'moment';
import MockDate from 'mockdate';

describe('relativeDateTime', () => {
    beforeEach(() => {
        MockDate.set('1/1/2016');
    });

    it('it can describe the duration since a certain time', () => {
        assert.equal('0m', formatDuration(moment()));
        assert.equal('0m', formatDuration(moment().subtract(10, 'seconds')));
        assert.equal('0m', formatDuration(moment().subtract(11, 'seconds')));
        assert.equal('0m', formatDuration(moment().subtract(59, 'seconds')));
        assert.equal('1m', formatDuration(moment().subtract(60, 'seconds')));
        assert.equal('1m', formatDuration(moment().subtract(119, 'seconds')));
        assert.equal('2m', formatDuration(moment().subtract(120, 'seconds')));
        assert.equal('1h 0m', formatDuration(moment().subtract(1, 'hour')));
        assert.equal(
            '1h 15m',
            formatDuration(
                moment()
                    .subtract(1, 'hour')
                    .subtract(15, 'minutes')
            )
        );
        assert.equal('2h 0m', formatDuration(moment().subtract(2, 'hour')));
        assert.equal('1d 0h 0m', formatDuration(moment().subtract(1, 'day')));
        assert.equal(
            '1d 2h 30m',
            formatDuration(
                moment()
                    .subtract(1, 'day')
                    .subtract(2, 'hours')
                    .subtract(30, 'minutes')
            )
        );
        assert.equal('2d 0h 0m', formatDuration(moment().subtract(2, 'day')));
    });

    it('it can describe how long a date time is in the past', () => {
        assert.equal('Just now', relativeDateTime(moment()));
        assert.equal('Just now', relativeDateTime(moment().subtract(10, 'seconds')));
        assert.equal('Just now', relativeDateTime(moment().subtract(11, 'seconds')));
        assert.equal('Just now', relativeDateTime(moment().subtract(59, 'seconds')));
        assert.equal('A minute ago', relativeDateTime(moment().subtract(60, 'seconds')));
        assert.equal('A minute ago', relativeDateTime(moment().subtract(119, 'seconds')));
        assert.equal('2 minutes ago', relativeDateTime(moment().subtract(120, 'seconds')));
        assert.equal('An hour ago', relativeDateTime(moment().subtract(1, 'hour')));
        assert.equal('2 hours ago', relativeDateTime(moment().subtract(2, 'hour')));
        assert.equal('A day ago', relativeDateTime(moment().subtract(1, 'day')));
        assert.equal('2 days ago', relativeDateTime(moment().subtract(2, 'day')));
        assert.equal('5 days ago', relativeDateTime(moment().subtract(5, 'day')));
        assert.equal('A long long time ago', relativeDateTime(moment().subtract(6, 'day')));
    });

    it('it can describe how far a date is in the future', () => {
        assert.equal('Today', relativeDate(moment()));
        assert.equal('Tomorrow', relativeDate(moment().add(1, 'day')));
        assert.equal(
            moment()
                .add(2, 'days')
                .format('dddd'),
            relativeDate(moment().add(2, 'days'))
        );
        assert.equal(
            moment()
                .add(7, 'days')
                .format('dddd'),
            relativeDate(moment().add(7, 'days'))
        );
        assert.equal('In 10 days', relativeDate(moment().add(10, 'days')));
        assert.equal('In a month', relativeDate(moment().add(30, 'days')));
        assert.equal('In 3 months', relativeDate(moment().add(80, 'days')));
    });

    afterEach(function() {
        MockDate.reset();
    });
});
