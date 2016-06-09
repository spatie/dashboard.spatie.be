import pusherChannel from '../helpers/pusher-channel';

export default {
    created() {

        const eventHandlers = this.getEventHandlers();

        Object.keys(eventHandlers).forEach(eventName => {
            pusherChannel.bind(eventName, eventHandlers[eventName]);
        });
    },
};
