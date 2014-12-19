Ext.define('Slims.view.users.Grid', {
    extend: 'Ext.grid.Panel',
    xtype: 'usersgrid',

    requires: ['Ext.grid.column.Action'],

    initComponent: function() {
        this.store = Ext.create('Slims.store.Users');

        this.columns = [{
            text: 'Username',
            dataIndex: 'username',
            width: 200
        }, {
            text: 'Name',
            dataIndex: 'name',
            flex: 1
        }, {
            text: 'Research Group',
            dataIndex: 'research_group',
            flex: 1,
            renderer: function(value) {
                return value ? value.name : '[No group selected]';
            }
        }, {
            text: 'Active?',
            dataIndex: 'is_active',
            width: 100,
            renderer: function(value) {
                return value ? 'Yes' : 'No';
            }
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
            text: 'Add user',
            icon: '/resources/images/add.png',
            name: 'addUser'
        }];

        this.callParent();

        this.on('afterrender', this.loadData, this);
    },

    loadData: function() {
        this.getStore().load();
    }
});