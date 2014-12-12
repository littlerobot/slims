Ext.define('App.view.Viewport', {
    extend: 'Ext.container.Viewport',
    requires: [
        'Ext.tab.Panel',
        'App.router.API',
        'App.store.User',
        'App.view.tree.ContainerGrid',
        'Ext.layout.container.Fit',
        'App.view.groups.Grid'
    ],
    layout: 'fit',
    title: 'Slims',
    items: [
        {
            xtype: 'tabpanel',
            title: '<span id="title">Slims</span>',
            items: [
                {
                    title: 'Container demo',
                    xtype: 'container-grid'
                },
                {
                    title: 'Research Groups',
                    xtype: 'researchgroups-grid'
                }
            ]
        }
    ]
});
