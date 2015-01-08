Ext.define('Slims.view.Viewport', {
    extend: 'Ext.container.Viewport',
    requires: [
        'Ext.tab.Panel',
        'Ext.layout.container.Fit',
        'Slims.view.groups.Grid',
        'Slims.view.home.Panel'
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
            border: true,
            title: 'Research Groups',
            icon: '/resources/images/groups.png'
        }, {
            xtype: 'usersgrid',
            border: true,
            title: 'Users',
            icon: '/resources/images/users.png'
        }, {
            xtype: 'templatespage',
            title: 'Templates',
            icon: '/resources/images/users.png'
        }]
    }]
});