Ext.Loader.setConfig('disableCaching', false);

Ext.application({
    name: 'App',
    appFolder: 'app',
    autoCreateViewport: true,
    controllers: [
        'Session'
    ],
    launch: function() {
    }
});