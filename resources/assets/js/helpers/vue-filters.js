import Vue from 'vue';
import { formatNumber, gridFromTo, modifyClass, relativeDate } from './helpers';

Vue.filter('relative-date', relativeDate);

Vue.filter('format-number', formatNumber);

Vue.filter('grid-from-to', gridFromTo);

Vue.filter('modify-class', modifyClass);
