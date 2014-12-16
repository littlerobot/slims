Ext.define('App.view.groups.Grid', {
    extend: 'Ext.grid.Panel',
    xtype: 'researchgroups-grid',

    requires: ['Ext.grid.column.Action'],

    initComponent: function() {
        this.store = Ext.create('App.store.ResearchGroups');

        this.columns = [{
            text: 'Name',
            dataIndex: 'name',
            flex: 1
        }, {
            xtype: 'actioncolumn',
            width: 50,
            items: [{
                icon: '/images/edit_icon.png',
                tooltip: 'Edit',
                scope:  this,
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    this.fireEvent('editrecord', rec);
                }
            },{
                icon: '/images/delete.gif',
                tooltip: 'Delete',
                hidden: true,
                getClass: function(v, meta) {
                    meta.style = 'padding-left: 10px;';
                    return v;
                },
                scope:  this,
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    this.fireEvent('deleterecord', rec);
                }
            }]
        }];

        this.tbar = [{
            text: 'Add group',
            icon: '/images/add.png',
            name: 'addGroup'
        }];

        this.callParent();

        this.on('afterrender', this.loadData, this);
    },

    loadData: function() {
        this.getStore().load();
    }
});