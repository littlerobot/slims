Ext.define('App.view.tree.TreeGrid', {
    extend: 'Ext.tree.Panel',

    requires: [
        'Ext.data.*',
        'Ext.grid.*',
        'Ext.tree.*',
        'Ext.ux.CheckColumn',
        'App.model.Container'
    ],
    xtype: 'tree-grid',
    reserveScrollbar: true,
    useArrows: true,
    rootVisible: false,
    multiSelect: true
});
