Ext.define('Slims.view.templates.AttributeWindow', {
    extend: 'Ext.window.Window',
    xtype: 'attributewindow',

    requires: [
        'Ext.form.field.Text',
        'Ext.toolbar.Toolbar',
        'Ext.form.field.Checkbox'
    ],

    width: 400,
    layout: 'vbox',
    modal: true,
    attribute: null,

    initComponent: function() {
        this.setWindowTitle();

        this.items = [{
            xtype: 'form',
            style: 'padding: 10px;',
            width: '100%',
            defaults: {
                anchor: '100%',
                labelWidth: 80
            },
            items: [{
                xtype: 'textfield',
                name: 'name',
                allowBlank: false,
                fieldLabel: 'Label'
            }, {
                xtype: 'combobox',
                fieldLabel: 'Type',
                allowBlank: false,
                editable: false,
                store: {
                    fields: ['name'],
                    data: this.getOptionsList()
                },
                value: 'brief-text',
                valueField: 'name',
                displayField: 'name',
                listeners: {
                    scope: this,
                    change: function(field, val) {
                        if (val == 'option') {
                            this.showOptionsTools();
                        } else {
                            this.hideOptionsTools();
                        }
                    }
                }
            }]
        }, {
            xtype: 'panel',
            style: 'padding: 10px;',
            width: '100%',
            border: true,
            name: 'options',
            hidden: true,
            items: [{
                xtype: 'grid',
                height: 300,
                store: {
                    data: [],
                    fields: ['name']
                },
                columns: [{
                    text: 'Name',
                    flex: 1,
                    dataIndex: 'name'
                }, {
                    xtype: 'actioncolumn',
                    width: 50,
                    menuDisabled: true,
                    items: [{
                        icon: '/resources/images/edit.png',
                        iconCls: 'slims-actions-icon-marginright',
                        tooltip: 'Delete',
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
                            var rec = grid.getStore().getAt(rowIndex);
                            this.fireEvent('deleterecord', rec);
                        }
                    }]
                }],
                tbar: [{
                    text: 'Add option',
                    icon: '/resources/images/add.png',
                    name: 'addOption'
                }]
            }]
        }];


        this.bbar = ['->', {
            text: 'Save',
            icon: '/resources/images/save.png',
            width: 80,
            name: 'save',
            scope: this,
            handler: function() {
                if (!this.down('form').getForm().isValid())
                    return;

                this.fireEvent('save', null, this);
            }
        }, {
            text: 'Cancel',
            icon: '/resources/images/cancel.png',
            width: 80,
            scope: this,
            handler: this.close
        }];

        this.on('afterrender', this.setupData, this);

        this.callParent();
    },

    setupData: function() {
        if (!this.attribute)
            return;

        var isOptions = (this.attribute.type == 'option')
        if (isOptions) {
            this.showOptionsTools(this.attribute.options);
        }
    },

    showOptionsTools: function(options) {
        var optionsPanel = this.down('panel[name=options]');

        optionsPanel.setVisible(true);
        optionsPanel.down('grid').getStore().loadData(options || []);
    },

    hideOptionsTools: function() {
        var optionsPanel = this.down('panel[name=options]');

        optionsPanel.setVisible(false);
    },

    setWindowTitle: function() {
        if (this.attribute == null) {
            this.title = 'Add new attribute';
        } else {
            this.title = 'Edit attribute';
        }
    },

    getOptionsList: function() {
        return [{
            name: 'brief-text'
        }, {
            name: 'long-text'
        }, {
            name: 'document'
        }, {
            name: 'option'
        }, {
            name: 'date'
        }, {
            name: 'colour'
        }, {
            name: 'user'
        }];
    }
});