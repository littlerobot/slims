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

    title: 'Container management',
    height: 300,
    useArrows: true,
    rootVisible: false,
    multiSelect: true,

    initComponent: function() {
        this.width = 600;

        Ext.apply(this, {
            store: new Ext.data.TreeStore({
                model: App.model.Container,
                proxy: {
                    type: 'ajax',
                    url: '/api/containertree.json',
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
                flex: 2,
                sortable: true,
                dataIndex: 'name'
            },{
                text: 'Owned by',
                flex: 1,
                sortable: true,
                dataIndex: 'owner'
            }, {
                text: 'Action',
                width: 55,
                menuDisabled: true,
                xtype: 'actioncolumn',
                tooltip: 'Edit task',
                align: 'center',
                icon: '/web/images/edit_icon.png',
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
