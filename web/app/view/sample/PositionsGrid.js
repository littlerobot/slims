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
                if (this.getStore().data.length > 0) {
                    this.fireEvent('configure', this);
                } else {
                    Ext.Msg.alert('Grid is empty', 'No data to configure. Please, select postitons first.');
                }
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
            xtype: 'typecolumn',
            type: 'colour',
            dataIndex: 'samplesColor',
            width: 30
        }, {
            xtype: 'actioncolumn',
            icon: '/resources/images/edit.png',
            width: 30,
            menuDisabled: true,
            tooltip: 'Edit',
            scope: this,
            isDisabled: function(view, rowIndex, colIndex, item, record) {
                return !record.get('samplesColor');
            },
            handler: function(grid, rowIndex) {
                var rec = grid.getStore().getAt(rowIndex);
                this.fireEvent('editrecord', rec);
            }
        }];

        this.columns = this.forwardBlock.concat(this.backBlock);

        this.callParent();
    },

    refreshStoreAttributes: function() {
        if (this.configureData) {
            this.buildStoreAttributes(this.configureData);
        }
    },

    buildStoreAttributes: function(data) {
        data = data || {
            storeAttributes: [],
            storeAttributesValues: {}
        };
        this.configureData = data;

        var extraColumns = [];
        Ext.each(data.storeAttributes, function(attr) {
            extraColumns.push({
                xtype: 'typecolumn',
                type: attr.get('type'),
                dataIndex: 'attributes.id'+attr.get('id'),
                width: 150,
                text: attr.get('label')
            });
        }, this);

        if (extraColumns.length > 0) {
            this.configured = true;
        }
        var columns = this.forwardBlock.concat(extraColumns).concat(this.backBlock);

        // add dynamic attributes into store model
        var storeFields = this.staticFields.concat(data.storeAttributes.map(function(attr) {
            return 'attributes.id'+attr.get('id')
        }));

        var store = Ext.create('Ext.data.Store', {fields: storeFields});
        var storeItems = this.getStore().data.items;

        var attributesValues = data ? data.storeAttributesValues : {},
            attributes = {};

        for (var id in attributesValues) {
            attributes['id'+id] = attributesValues[id];
        }
        // copy attributes data into each sample
        var data = storeItems.map(function(record) {
            var vals = record.data;
            vals.sampleInstanceTemplate = vals.sampleInstanceTemplate || data.sampleInstanceId || '';
            vals.sampleType = vals.sampleType || data.sampleType || '';
            vals.samplesColor = vals.samplesColor || data.samplesColor;
            vals.attributes = vals.attributes || attributes;
            return vals;
        });

        this.reconfigure(store, columns);

        this.getStore().loadRawData(data);
    },

    reset: function() {
        this.configured = false;
        this.getStore().removeAll();
        this.buildStoreAttributes();
    }
});
