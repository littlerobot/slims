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

        this.columns = [{
            text: 'Position ID',
            width: 100,
            dataIndex: 'positionId'
        }, {
            text: 'Sample Type',
            width: 150,
            dataIndex: 'sampleType'
        }, {
            text: 'Sample Instance Template',
            flex: 1,
            dataIndex: 'sampleInstanceTemplate'
        }, {
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

        this.callParent();
    }
});