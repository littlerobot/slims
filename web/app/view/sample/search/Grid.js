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

    viewConfig: {
        listeners: {
            refresh: function(dataview) {
                dataview.panel.columns[1].autoSize();
            }
        }
    },

    showSearchResults: function(results) {
        var instanceColumns = [],
            typeColumns = [],
            attributes = {},
            FILE_HTML = '<a href="{0}" target="_blank">Open file</a>';

        var data = results.map(function(sample) {
            Ext.each(sample.instance_attributes, function(attr) {
                var lbl = attr.label.split(' ').join('');
                var id = lbl+attr.type,
                    column = {
                        xtype: 'typecolumn',
                        type: attr.type,
                        dataIndex: id,
                        text: attr.label,
                        width: 150,
                        sortable: true
                    };

                // prevent default renderer for document
                if (attr.type == 'document') {
                    delete column.type;
                    sample[id] = attr.url ? Ext.String.format(FILE_HTML, attr.url) : '';
                } else {
                    sample[id] = attr.value;
                }

                if (!attributes[id]) {
                    instanceColumns.push(column);
                    attributes[id] = attr.value || true;
                }
            }, this);

            Ext.each(sample.type_attributes, function(attr) {
                var id = 'ta'+attr.id,
                    column = {
                        xtype: 'typecolumn',
                        type: attr.type,
                        dataIndex: id,
                        text: attr.label,
                        width: 150
                    };

                // prevent default renderer for document
                if (attr.type == 'document') {
                    delete column.type;
                    sample[id] = attr.url ? Ext.String.format(FILE_HTML, attr.url) : '';
                } else {
                    sample[id] = attr.value;
                }

                if (!attributes[id]) {
                    typeColumns.push(column);
                    attributes[id] = attr.value || true;
                }
            }, this);

            return sample;
        });

        var columns = this.getDefaultColumns(),
            fields = columns.concat(typeColumns).concat(instanceColumns).map(function(column) { return column.dataIndex; }),
            columnModel = columns;

        if (typeColumns.length) {
            columnModel = columnModel.concat({
                text: 'Type attributes',
                columns: typeColumns
            })
        }

        columnModel = columnModel.concat({
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
