Ext.define('Slims.view.home.Grid', {
    extend: 'Ext.tree.Panel',
    xtype: 'containergrid',

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
            // TODO: extract this store
            store: new Ext.data.TreeStore({
                model: Slims.model.Container,
                proxy: {
                    type: 'ajax',
                    url: Slims.Url.getRoute('containers'),
                    reader: {
                        type: 'json',
                        root: 'data',
                        totalProperty: 'count',
                        implicitIncludes: true
                    }
                },
                folderSort: true
            }),
            columns: [{
                xtype: 'treecolumn', //this is so we know which column will show the tree
                text: 'Container',
                flex: 3,
                sortable: true,
                dataIndex: 'name'
            }, {
                text: 'Remaining capacity',
                width: 140,
                dataIndex: 'sample_remaining_capacity'
            }, {
                text: 'Total capacity',
                width: 120,
                dataIndex: 'sample_total_capacity'
            }, {
                text: 'Research group',
                flex: 2,
                dataIndex: 'research_group'
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
                    Ext.Msg.alert('Action', 'This is a sample action that can be performed on "' + record.get('name') + '"');
                },
                isDisabled: function(view, rowIdx, colIdx, item, record) {
                    return false
                }
            }]
        });
        this.callParent();
    }
});
