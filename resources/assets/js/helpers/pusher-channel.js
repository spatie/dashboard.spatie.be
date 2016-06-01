import Pusher from 'pusher-js';

const pusher = new Pusher('a3ac6737590aaf2427db', {
    cluster: 'eu',
    authEndpoint: '/pusher/authenticate',
});

const pusherChannel = pusher.subscribe('private-dashboard');

export default pusherChannel;