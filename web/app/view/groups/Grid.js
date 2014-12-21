Ext.define('Slims.view.groups.Grid', {
    extend: 'Ext.grid.Panel',
    xtype: 'groupsgrid',

    requires: ['Ext.grid.column.Action'],


    initComponent: function() {
        this.store = Ext.StoreMgr.get('researchGroups');

        this.columns = [{
            text: 'Name',
            dataIndex: 'name',
            flex: 1
        }, {
            xtype: 'actioncolumn',
            width: 30,
            items: [{
                icon: '/resources/images/edit.png',
                tooltip: 'Edit',
                scope:  this,
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    this.fireEvent('editrecord', rec);
                }
            }]
        }];

        this.tbar = [{
            text: 'Add group',
            icon: '/resources/images/add.png',
            name: 'addGroup'
        }, '->', {
            width: 25,
            icon: '/resources/images/reload.png',
            name: 'reloadGrid'
        }];

        this.callParent();
    }
});