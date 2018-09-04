import { forIn } from 'lodash';

export default {
    created() {
        forIn(this.getEventHandlers(), (handler, eventName) => {
            this.$root.echo.private('dashboard').listen(eventName, response => handler(response));
        });
    },
};
