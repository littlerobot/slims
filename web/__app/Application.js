Ext.Loader.setConfig({
    enabled: true,
    disableCaching: false
});

Ext.application({
    name: 'Slims',
    appFolder: '/app',
    autoCreateViewport: true,
    controllers: [
        'Session',
        'Login',
        'ResearchGroups'
    ],
    launch: function () {
    }
});
