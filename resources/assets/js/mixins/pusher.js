import { forIn } from 'lodash';
import pusherChannel from '../helpers/pusher-channel';

export default {
    created() {
        forIn(this.getEventHandlers(), (handler, event) => {
            pusherChannel.bind(event, handler.bind(this));
        });
    },
};
