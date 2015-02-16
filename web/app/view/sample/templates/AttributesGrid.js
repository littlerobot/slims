Ext.define('Slims.view.sample.templates.AttributesGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'sampleattributesgrid',

    requires: [
        'Ext.grid.column.Action',
        'Ext.grid.plugin.DragDrop'
    ],

    style: 'border-top: 1px solid #157fcc !important;',

    initComponent: function() {
        this.store = Ext.create('Slims.store.sample.Attributes');

        this.columns = [{
            text: '#',
            dataIndex: 'order',
            width: 40
        }, {
            text: 'Name',
            dataIndex: 'label',
            width: 220
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
                    this.fireEvent('editrecord', rec, this);
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
            dragText: 'Just drop in a new place'
        });

        this.viewConfig = {
            plugins: this.ddPlugin,
            allowCopy: true,
            listeners: {
                scope: this,
                 beforedrop: function(node, data, overModel, dropPosition, dropHandlers) {
                    var attribute = data.records[0],
                        label = attribute.get('label'),
                        id = attribute.get('id');

                    Ext.each(this.getStore().data.items, function(item, i) {
                        if ((item.get('label') == label) && (item.get('id') != id)) {
                            dropHandlers.cancelDrop();
                            Ext.Msg.alert('Operation canceled', 'Record already have attribute with this label');
                        }
                    }, this);
                },
                drop: function(node, data) {
                    var attribute = data.records[0];
                    if (data.copy) {
                        console.log(1);
                        attribute.set('id', null);
                    }
                    this.updateAttributesOrder(this.getStore().data);
                }
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

        this.fireEvent('attributeschanged', attributes, this);
    }
});