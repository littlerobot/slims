Ext.define('Slims.view.Viewport', {
    extend: 'Ext.container.Viewport',
    requires: [
        'Ext.tab.Panel',
        'Slims.view.tree.ContainerGrid',
        'Ext.layout.container.Fit',
        'Slims.view.groups.Grid'
    ],
    layout: 'fit',
    title: 'Slims',
    items: [{
        xtype: 'tabpanel',
        title: '<span id="title">Slims</span>',
        items: [{
            title: 'Container demo',
            xtype: 'container-grid'
        }, {
            title: 'Research Groups',
            icon: '/resources/images/groups.png',
            xtype: 'researchgroups-grid'
        }, {
            title: 'Users',
            icon: '/resources/images/users.png',
            xtype: 'users-grid'
        }]
    }]
});