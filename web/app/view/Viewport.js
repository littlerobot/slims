Ext.define('Slims.view.Viewport', {
    extend: 'Ext.container.Viewport',
    requires: [
        'Ext.tab.Panel',
        'Ext.layout.container.Fit',
        'Ext.layout.container.Border',
        'Slims.view.groups.Grid',
        'Slims.view.home.Panel',
        'Slims.view.sample.Page'
    ],

    layout: 'fit',
    title: 'Slims',

    items: [{
        xtype: 'tabpanel',
        title: '<span id="title">Slims</span>',
        items: [{
            xtype: 'homepage',
            title: 'Home',
            icon: '/resources/images/home.png'
        }, {
            xtype: 'groupsgrid',
            title: 'Research Groups',
            icon: '/resources/images/groups.png'
        }, {
            xtype: 'usersgrid',
            title: 'Users',
            icon: '/resources/images/users.png'
        }, {
            xtype: 'tabpanel',
            title: 'Samples',
            tabPosition: 'left',
            icon: '/resources/images/template.png',
            items: [{
                xtype: 'samplespage',
                title: 'Manage Samples'
            }, {
                xtype: 'sampletypespage',
                title: 'Manage Sample Types',
                icon: '/resources/images/template.png'
            }, {
                xtype: 'sampletemplatespage',
                title: 'Manage Instance Templates',
                icon: '/resources/images/template.png'
            }, {
                xtype: 'templatespage',
                title: 'Manage Type Templates',
                icon: '/resources/images/template.png'
            }]
        }]
    }]
});