Ext.define('App.view.groups.Grid', {
    extend: 'Ext.grid.Panel',
    xtype: 'researchgroups-grid',

    requires: ['Ext.grid.column.Action'],

    initComponent: function() {
        // TODO: store to separated class
        this.store = Ext.create('Ext.data.Store', {
            fields: ['id', 'name'],
            data: [{
                id: 1,
                name: 'Group name #1'
            }, {
                id: 2,
                name: 'Group name #2'
            }]
        });

        this.columns = [{
            text: 'Name',
            dataIndex: 'name',
            flex: 1
        }, {
            xtype: 'actioncolumn',
            width: 50,
            items: [{
                icon: 'images/edit_icon.png',
                tooltip: 'Edit',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    alert("Edit " + rec.get('name'));
                }
            },{
                icon: 'images/delete.gif',
                tooltip: 'Delete',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    alert("Terminate " + rec.get('name'));
                }
            }]
        }];

        this.tbar = [{
            text: 'Add group',
            icon: 'images/add.png',
            style: 'padding-left: 5px;',
            scope: this,
            handler: function(btn) {
                this.fireEvent('addgroup', this, btn);
            }
        }];

        this.callParent();
    }
});