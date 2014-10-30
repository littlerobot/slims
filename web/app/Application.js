Ext.Loader.setConfig({
    enabled: true,
    disableCaching: false
});

Ext.application({
    name: 'App',
    appFolder: '/app',
    autoCreateViewport: true,
    controllers: [
        'Session',
        'Login'
    ],
    launch: function () {
    }
});
