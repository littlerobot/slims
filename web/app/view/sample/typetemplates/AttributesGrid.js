Ext.define('Slims.view.sample.typetemplates.AttributesGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'attributesgrid',

    requires: [
        'Ext.grid.column.Action',
        'Ext.grid.plugin.DragDrop'
    ],

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
            width: 120,
            renderer: function(type) {
                return type ? Ext.StoreManager.get('attributeTypes').getById(type).get('name') : '';
            }
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
                tooltip: 'Edit',
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
                    grid.getStore().removeAt(rowIndex);
                    this.updateAttributesOrder(this.getStore().data);
                }
            }]
        }];

        this.tbar = [{
            text: 'Add attribute',
            disabled: true,
            icon: '/resources/images/add.png',
            name: 'addAttribute'
        }];

        this.viewConfig = this.viewConfig || {};

        this.ddPlugin = Ext.create('Ext.grid.plugin.DragDrop', {
            dragText: 'Drop on a new place to change order'
        });

        this.viewConfig.plugins = this.ddPlugin;
        this.viewConfig.listeners = {
            scope: this,
            drop: function() {
                this.updateAttributesOrder(this.getStore().data);
            }
        };


        this.callParent();

        this.on('afterrender', this.loadData, this);
    },

    loadData: function() {
        this.getStore().load();
    },

    updateAttributesOrder: function(data) {
        var attributes = [];

        Ext.each(data.items, function(r, index) {
            var attribute = r.data;
            attribute.order = index + 1;

            attributes.push(attribute);
        });

        this.getStore().loadData(attributes);

        this.fireEvent('attributeschanged', attributes);
    }
});