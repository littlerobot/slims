Ext.Loader.setConfig({
    enabled: true,
    disableCaching: false
});

Ext.define('Slims.Application', {
    name: 'Slims',

    extend: 'Ext.app.Application',

    requires: [
        'Slims.router.Api',
        'Ext.tip.QuickTipManager'
    ],

    views: [],

    controllers: [
        'Main',
        'Home',
        'ResearchGroups',
        'Users',
        'Templates',
        'SampleTemplates',
        'SampleTypes',
        'Samples',
        'SamplesSearch'
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
