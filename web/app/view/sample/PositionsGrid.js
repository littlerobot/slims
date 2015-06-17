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
            text: 'Configure Samples',
            handler: function() {
                this.fireEvent('configure', this);
            },
            scope: this
        }];

        this.staticFields = ['positionId', 'sampleType', 'sampleInstanceTemplate', 'samplesColor'];

        this.forwardBlock = [{
            text: 'Position ID',
            width: 100,
            dataIndex: 'positionId'
        }, {
            text: 'Type',
            width: 140,
            hidden: true,
            dataIndex: 'sampleType'
        }, {
            text: 'Instance Template',
            width: 140,
            hidden: true,
            dataIndex: 'sampleInstanceTemplate'
        }];

        this.backBlock = [{
            width: 30,
            dataIndex: 'samplesColor',
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
            handler: function(grid, rowIndex) {
                var rec = grid.getStore().getAt(rowIndex);
                this.fireEvent('editrecord', rec);
            }
        }];

        this.columns = this.forwardBlock.concat(this.backBlock);

        this.callParent();
    },

    buildStoreAttributes: function(data) {
        data = data || {
            storeAttributes: [],
            storeAtrributesValues: {}
        };

        var extraColumns = [];
        Ext.each(data.storeAttributes, function(attr) {
            extraColumns.push({
                dataIndex: 'attributes.id'+attr.get('id'),
                width: 150,
                text: attr.get('label')
            });
        }, this);

        var columns = this.forwardBlock.concat(extraColumns).concat(this.backBlock);

        // add dynamic attributes into store model
        var storeFields = this.staticFields.concat(data.storeAttributes.map(function(attr) {
            return 'attributes.id'+attr.get('id')
        }));

        var store = Ext.create('Ext.data.Store', {fields: storeFields});
        var storeItems = this.getStore().data.items;

        var attributesValues = data ? data.storeAtrributesValues : {},
            attributes = {};

        for (var id in attributesValues) {
            attributes['id'+id] = attributesValues[id];
        };
        // copy attributes data into each sample
        var data = storeItems.map(function(record) {
            var vals = record.data;
            vals.sampleInstanceTemplate = data.sampleInstanceId || '';
            vals.sampleType = data.sampleType || '';
            vals.samplesColor = data.samplesColor || vals.samplesColor;
            vals.attributes = attributes;
            return vals;
        });

        this.reconfigure(store, columns);

        this.getStore().loadRawData(data);
    }
});