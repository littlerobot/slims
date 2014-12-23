Ext.define('Slims.view.home.Grid', {
    extend: 'Ext.tree.Panel',
    xtype: 'containersgrid',

    requires: [
        'Ext.Msg',
        'Ext.ux.CheckColumn',
        'Slims.model.Container'
    ],

    reserveScrollbar: true,
    useArrows: true,
    rootVisible: false,
    multiSelect: true,

    initComponent: function() {
        Ext.apply(this, {
            tbar: [{
                xtype: 'button',
                text: 'Add Container',
                icon: '/resources/images/add.png',
                name: 'addContainer'
            }, , '->', {
                width: 25,
                icon: '/resources/images/reload.png',
                name: 'reloadGrid'
            }],
            store: Ext.create('Slims.store.Containers'),
            columns: [{
                xtype: 'treecolumn',
                text: 'Container',
                flex: 3,
                sortable: true,
                dataIndex: 'name'
            }, {
                text: 'Research group',
                flex: 2,
                dataIndex: 'research_group',
                renderer: function(value) {
                    return value ? value.name : '';
                }
            }, {
                text: 'Capacity',
                style: 'text-align: middle;',
                columns: [{
                    text: 'Remaining',
                    width: 85,
                    dataIndex: 'sample_remaining_capacity'
                }, {
                    text: 'Total',
                    width: 55,
                    dataIndex: 'sample_total_capacity'
                }]
            }, {
                width: 30,
                dataIndex: 'colour',
                renderer: function(value) {
                    return '<div style="width: 15px; height: 15px; background-color: '+value+'; border: 1px solid black;">&nbsp;</div>';
                }
            }, {
                xtype: 'actioncolumn',
                icon: '/resources/images/edit.png',
                width: 30,
                menuDisabled: true,
                align: 'center',
                tooltip: 'Edit',
                handler: function(grid, rowIndex, colIndex, actionItem, event, record, row) {

                }
            }]
        });
        this.callParent();
    }
});
