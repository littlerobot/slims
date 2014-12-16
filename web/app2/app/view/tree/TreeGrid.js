Ext.define('Slims.view.tree.TreeGrid', {
    extend: 'Ext.tree.Panel',

    // TODO: check nesessary of such requres list
    requires: [
        'Ext.data.*',
        'Ext.grid.*',
        'Ext.tree.*',
        'Ext.ux.CheckColumn',
        'Slims.model.Container'
    ],
    xtype: 'tree-grid',
    reserveScrollbar: true,
    useArrows: true,
    rootVisible: false,
    multiSelect: true
});
