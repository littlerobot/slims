Ext.define('Slims.view.templates.AttributesGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'attributesgrid',

    requires: ['Ext.grid.column.Action'],
    style: 'border-top: 1px solid #157fcc !important;',

    initComponent: function() {
        this.store = Ext.create('Slims.store.Attributes');

        this.columns = [{
            text: 'Order',
            dataIndex: 'order',
            width: 100
        }, {
            text: 'Name',
            dataIndex: 'label',
            width: 320
        }, {
            text: 'Type',
            dataIndex: 'type',
            width: 120
        }, {
            text: 'Details',
            dataIndex: 'options',
            flex: 1
        }, {
            xtype: 'actioncolumn',
            width: 50,
            menuDisabled: true,
            items: [{
                icon: '/resources/images/edit.png',
                iconCls: 'slims-actions-icon-marginright',
                tooltip: 'Delete',
                scope: this,
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    this.fireEvent('editrecord', rec);
                }
            }, {
                icon: '/resources/images/delete.png',
                tooltip: 'Delete',
                scope: this,
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    this.fireEvent('deleterecord', rec);
                }
            }]
        }];

        this.tbar = [{
            text: 'Add attribute',
            disabled: true,
            icon: '/resources/images/add.png',
            name: 'addAttribute'
        }];

        this.callParent();

        this.on('afterrender', this.loadData, this);
    },

    loadData: function() {
        this.getStore().load();
    }
});