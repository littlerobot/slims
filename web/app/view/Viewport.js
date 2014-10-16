Ext.define('App.view.Viewport', {
    extend: 'Ext.container.Viewport',
    requires: [
        'Ext.tab.Panel',
        'App.router.API',
        'App.store.User'
    ],
    layout: 'fit',
    title: 'Slims',
    items: [
        {
            xtype: 'tabpanel',
            title: '<span id="title">Slims</span>'
        }
    ]
});
