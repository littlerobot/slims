Ext.define('Slims.view.sample.instancetemplates.TemplatesGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'instancetemplatesgrid',

    requires: ['Ext.grid.column.Action'],

    initComponent: function() {
        this.store = Ext.StoreMgr.get('instanceTemplates');

        this.columns = [{
            text: 'Name',
            dataIndex: 'name',
            flex: 1
        }, {
            text: '',
            dataIndex: 'editable',
            width: 70,
            renderer: function(isEditable, meta) {
                if (isEditable) {
                    meta.style = 'color: green;';
                    return 'editable';
                } else {
                    meta.style = 'color: red;';
                    return 'in use';
                }
            }
        }, {
            xtype: 'actioncolumn',
            width: 30,
            menuDisabled: true,
            items: [{
                icon: '/resources/images/edit.png',
                tooltip: 'Edit name',
                isDisabled: function(view, col, row, item, record) {
                    return !record.get('editable');
                },
                scope: this,
                handler: function(grid, rowIndex, colIndex) {
                    var template = grid.getStore().getAt(rowIndex);
                    this.fireEvent('editrecord', template);
                }
            }]
        }];

        this.tbar = [{
            text: 'Add template',
            icon: '/resources/images/add.png',
            name: 'addTemplate'
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
