Ext.Loader.setConfig({
    enabled: true,
    disableCaching: false
});

Ext.define('Slims.Application', {
    name: 'Slims',

    extend: 'Ext.app.Application',

    requires: [
        'Slims.router.API'
    ],

    views: [
        // TODO: add views here
    ],

    controllers: [
        'ResearchGroups'
    ],

    stores: [
        'ResearchGroups'
    ],

    models: [
        'Container',
        'ResearchGroup'
    ]
});
