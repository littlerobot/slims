Ext.Loader.setConfig('disableCaching', false, 'paths', {'App': '/app'});

Ext.application({
    name: 'App',
    appFolder: 'app',
    autoCreateViewport: true,
    controllers: [
        'Session',
        'Login'
    ],
    launch: function () {
    }
});
