Ext.define('Slims.view.sample.PositionsGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'positionsgrid',

    layout: 'card',
    border: false,

    initComponent: function() {
        this.store = Ext.create('Ext.data.Store', {
            model: 'Slims.model.sample.Sample'
        });

        this.tbar = [{
            text: 'Configure All',
            handler: function() {
                this.fireEvent('configure', this);
            },
            scope: this
        }];

        this.forwardBlock = [{
            text: 'Position ID',
            width: 100,
            dataIndex: 'positionId'
        }]

        this.backBlock = [{
            width: 30,
            dataIndex: 'colour',
            renderer: function(value) {
                return Ext.String.format('<div style="width: 15px; height: 15px; background-color: {0}; border: 1px solid black;">&nbsp;</div>', value);
            }
        }, {
            xtype: 'actioncolumn',
            icon: '/resources/images/edit.png',
            width: 30,
            menuDisabled: true,
            tooltip: 'Edit',
            scope: this,
            handler: function(grid, rowIndex, colIndex) {
                var rec = grid.getStore().getAt(rowIndex);
                this.fireEvent('editrecord', rec);
            }
        }];

        this.columns = this.forwardBlock.concat(this.backBlock);

        this.callParent();
    },

    buildStoreAttributes: function(extraColumns, extraValues) {
        extraColumns = extraColumns || [];
        extraValues = extraValues || {};

        var columns = this.forwardBlock.concat(extraColumns).concat(this.backBlock);
        var storeFields = columns.map(function(f) {
            return f.dataIndex ? f.dataIndex.toString() : null
        });

        storeFields.pop(); // remove actioncolumn

        var store = Ext.create('Ext.data.Store', {fields: storeFields});
        var storeItems = this.getStore().data.items;

        var data = storeItems.map(function(record) {
            var vals = record.data;
            return Ext.merge(vals, extraValues);
        });

        this.reconfigure(store, columns);

        this.getStore().loadData(data);
    }
});