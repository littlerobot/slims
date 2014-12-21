Ext.Loader.setConfig({
    enabled: true,
    disableCaching: false
});

Ext.define('Slims.Application', {
    name: 'Slims',

    extend: 'Ext.app.Application',

    requires: [
        'Slims.router.Api'
    ],

    views: [],

    controllers: [
        'Main',
        'ResearchGroups',
        'Users'
    ],

    stores: [
        'ResearchGroups',
        'Users'
    ],

    models: [
        'Container',
        'ResearchGroup',
        'User'
    ]
});
