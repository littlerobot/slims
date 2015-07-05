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

    initComponent: function() {
        this.items = [{
            xtype: 'tabpanel',
            title: '<span id="title">Slims</span>',
            items: [{
                xtype: 'homepage',
                title: 'Home',
                icon: '/resources/images/home.png'
            }, {
                xtype: 'groupsgrid',
                padding: 5,
                border: true,
                title: 'Research Groups',
                icon: '/resources/images/groups.png'
            }, {
                xtype: 'usersgrid',
                title: 'Users',
                padding: 5,
                border: true,
                icon: '/resources/images/users.png'
            }, {
                xtype: 'samplespage',
                title: 'Samples',
                icon: '/resources/images/template.png'
            }]
        }];
        this.callParent();
    }
});