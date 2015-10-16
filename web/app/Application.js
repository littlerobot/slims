Ext.Loader.setConfig({
    enabled: true,
    disableCaching: false,
    paths: {
        'Ext.ux': 'app/ux'
    }
});

Ext.define('Slims.Application', {
    name: 'Slims',

    extend: 'Ext.app.Application',

    requires: [
        'Slims.router.Api',
        'Ext.tip.QuickTipManager',
        'Ext.ux.ClearButton',
        'Slims.ux.FileField',
        'Slims.ux.Utils',
        'Slims.ux.TypeColumn'
    ],

    views: [],

    controllers: [
        'Main',
        'Home',
        'ResearchGroups',
        'Users',
        'TypeTemplates',
        'InstanceTemplates',
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
