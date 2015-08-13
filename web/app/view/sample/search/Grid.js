Ext.define('Slims.view.sample.search.Grid', {
    extend: 'Ext.grid.Panel',
    xtype: 'samplesearch',
    stateful: true,

    initComponent: function() {
        this.store = Ext.create('Ext.data.Store', {
            fields: [
                'container_name',
                'instance_template_name',
                'type_name'
            ]
        });

        this.columns = [{
            dataIndex: 'container_name',
            text: 'Container',
            width: 150
        }, {
            dataIndex: 'type_name',
            text: 'Sample Type',
            width: 150
        }, {
            dataIndex: 'instance_template_name',
            text: 'Template',
            width: 150
        }];

        this.callParent();
    },

    showSearchResults: function(results) {
        var columns = this.columns.map(function(column) {
            return {
                dataIndex: column.dataIndex,
                text: column.text,
                width: column.width
            };
        });
        var instanceColumns = [];
        var typeColumns = [];
        var attributes = {};
        var attributesColumns = [];

        var data = results.map(function(sample) {
            Ext.each(sample.instance_attributes, function(attr) {
                var id = attr.label+attr.type,
                    column = {
                        dataIndex: id,
                        text: attr.label,
                        width: 150
                    };

                sample[id] = attr.value;
                if (!attributes[id]) {
                    attributesColumns.push(column);
                    instanceColumns.push(column);
                    attributes[id] = attr.value;
                }
            }, this);

            Ext.each(sample.type_attributes, function(attr) {
                var id = 'ta'+attr.id,
                    column = {
                        dataIndex: id,
                        text: attr.label,
                        width: 150
                    };

                sample[id] = attr.value;
                if (!attributes[id]) {
                    attributesColumns.push(column);
                    typeColumns.push(column);
                    attributes[id] = attr.value;
                }
            }, this);

            return sample;
        });

        // склеить все и взять список полей
        var fields = columns.concat(typeColumns).concat(instanceColumns).map(function(column) { return column.dataIndex; });

        var columnModel = columns.concat({text: 'Type attributes', columns: typeColumns}).concat({text: 'Instance attributes', columns: instanceColumns});
        // создать колумн модель с подгруппами

        store = Ext.create('Ext.data.Store', {
            fields: fields,
            data: results
        });

        this.reconfigure(store, columnModel);
    }
});