import pusherChannel from '../helpers/pusher-channel';

export default {
    created() {

        const eventHandlers = this.getEventHandlers();

        for (let eventName of Object.keys(eventHandlers)) {
            pusherChannel.bind(eventName, eventHandlers[eventName]);
        }
    },
};