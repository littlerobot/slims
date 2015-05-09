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
                title: 'Research Groups',
                icon: '/resources/images/groups.png'
            }, {
                xtype: 'usersgrid',
                title: 'Users',
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