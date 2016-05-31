import Pusher from 'pusher-js';

const pusher = new Pusher(_config.pusher.key, {
    // cluster: 'eu',
    authEndpoint: '/pusher/authenticate',
    wsHost: _config.pusher.host,
    wsPort: _config.pusher.port,
    disableStats: true,
    enabledTransports: [
        "ws",
        "flash",
    ],
    disabledTransports: [
        "flash",
    ],
});

const pusherChannel = pusher.subscribe('private-dashboard');

export default pusherChannel;
