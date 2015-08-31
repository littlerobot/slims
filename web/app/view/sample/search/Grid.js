Ext.define('Slims.view.sample.search.Grid', {
    extend: 'Ext.grid.Panel',
    xtype: 'samplesearch',
    stateful: true,

    initComponent: function() {
        this.store = Ext.create('Ext.data.Store', {
            fields: this.getDefaultFields()
        });

        this.columns = this.getDefaultColumns();


        this.viewConfig = {
            emptyText: '<center>No results</center>'
        };

        this.callParent();
    },

    showSearchResults: function(results) {
        var instanceColumns = [],
            typeColumns = [],
            attributes = {},
            attributesColumns = [];

        var data = results.map(function(sample) {
            Ext.each(sample.instance_attributes, function(attr) {
                var lbl = attr.label.split(' ').join('');
                var id = lbl+attr.type,
                    column = {
                        dataIndex: id,
                        text: attr.label,
                        width: 150
                    };

                sample[id] = attr.value;
                if (!attributes[id]) {
                    attributesColumns.push(column);
                    instanceColumns.push(column);
                    attributes[id] = attr.value || true;
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
                    attributes[id] = attr.value || true;
                }
            }, this);

            return sample;
        });

        console.log(instanceColumns);
        console.log(typeColumns);
        var columns = this.getDefaultColumns(),
            fields = columns.concat(typeColumns).concat(instanceColumns).map(function(column) { return column.dataIndex; }),
            columnModel = columns.concat({
                text: 'Type attributes',
                columns: typeColumns
            }).concat({
                text: 'Instance attributes',
                columns: instanceColumns
            });

        var store = Ext.create('Ext.data.Store', {
            fields: fields,
            data: data
        });

        this.reconfigure(store, columnModel);
    },

     getDefaultFields: function() {
        return [
            'container_name',
            'instance_template_name',
            'type_name'
        ];
    },

    getDefaultColumns: function() {
        return [{
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
    }
});