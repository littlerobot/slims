Ext.define('Slims.view.tree.ContainerGrid', {
    extend: 'Slims.view.tree.TreeGrid',
    requires: [
        'Slims.proxy.Rest'
    ],
    xtype: 'container-grid',
    title: 'Container management',
    height: 300,
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
                flex: 2,
                sortable: true,
                dataIndex: 'name'
            }, {
                text: 'Rows',
                flex: 0.5,
                sortable: true,
                dataIndex: 'rows'
            }, {
                text: 'Columns',
                flex: 0.5,
                sortable: true,
                dataIndex: 'columns'
            }, {
                text: 'Research group',
                flex: 2,
                sortable: true,
                dataIndex: 'research_group'
            }, {
                text: 'Colour',
                flex: 1,
                sortable: true,
                dataIndex: 'colour',
                renderer: function(value) {
                    return '<div style="width: 15px; height: 15px; background-color: '+value+'; border: 1px solid black;">&nbsp;</div>';
                }
            }, {
                text: 'Action',
                width: 55,
                menuDisabled: true,
                xtype: 'actioncolumn',
                tooltip: 'Edit',
                align: 'center',
                icon: '/images/edit_icon.png',
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
