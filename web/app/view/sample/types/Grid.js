Ext.define('Slims.view.sample.types.Grid', {
    extend: 'Ext.grid.Panel',
    xtype: 'sampletypesgrid',

    requires: ['Ext.grid.plugin.RowExpander'],
    border: true,

    plugins: [{
        ptype: 'rowexpander',
        rowBodyTpl: ['{[this.getAttributesHtml(values.attributes)]}', {
            getAttributesHtml: function(attributes) {
                var html = '';
                Ext.each(attributes, function(attr,i) {
                    var string = Ext.String.format('<div style="padding: 3px;"><b>{0}:</b> {1}</div>', attr.label, attr.value);
                    html += string;
                }, this);
                return html;
            }
        }]
    }],

    initComponent: function() {
        this.store = Ext.create('Slims.store.sample.Types');

        this.columns = [{
            text: 'ID',
            dataIndex: 'id',
            width: 100
        }, {
            text: 'Name',
            dataIndex: 'name',
            flex: 1
        }, {
            xtype: 'actioncolumn',
            width: 30,
            menuDisabled: true,
            items: [{
                icon: '/resources/images/edit.png',
                tooltip: 'Edit sample type',
                isDisabled: function(view, col, row, item, record) {
                    var isDisabled = !record.get('editable');
                    return false; //isDisabled;
                },
                scope: this,
                handler: function(grid, rowIndex, colIndex) {
                    var sampleType = grid.getStore().getAt(rowIndex);
                    this.fireEvent('editrecord', sampleType);
                }
            }]
        }];

        this.tbar = [{
            text: 'Add type',
            icon: '/resources/images/add.png',
            name: 'addType'
        }, '->', {
            width: 25,
            icon: '/resources/images/reload.png',
            name: 'reloadGrid'
        }];

        this.callParent();

        this.on('afterrender', this.loadData, this);
    },

    loadData: function() {
        this.getStore().load();
    }
});