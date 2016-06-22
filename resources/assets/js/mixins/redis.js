import io from 'socket.io-client';
import {forIn} from 'lodash';

var socket = io('http://localhost:6001');

export default {
    created() {
        forIn(this.getEventHandlers(), (handler, event) => {
            socket.on(event, function (data) {
                console.log('Event!');
                handler(data);
            });
        });
    }
};