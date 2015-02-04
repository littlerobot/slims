Ext.define('Slims.view.sample.templates.AttributeWindow', {
    extend: 'Ext.window.Window',
    xtype: 'sampleattributewindow',

    requires: [
        'Ext.form.field.Text',
        'Ext.toolbar.Toolbar',
        'Ext.form.field.Checkbox',
        'Ext.grid.plugin.RowEditing'
    ],

    width: 400,
    layout: 'vbox',
    modal: true,
    attribute: null,
    usedLabels: [],
    grid: null,

    initComponent: function() {
        this.setWindowTitle();

        if (this.attribute) {
            var currentLabelIndex = this.usedLabels.indexOf(this.attribute.get('label'));
            this.usedLabels.splice(currentLabelIndex, 1);
        }

        var rowEditor = Ext.create('Ext.grid.plugin.RowEditing', {
            clicksToMoveEditor: 1,
            autoCancel: false
        });

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
                name: 'label',
                allowOnlyWhitespace: false,
                fieldLabel: 'Label',
                validator: function(val) {
                    if (this.up('sampleattributewindow').usedLabels.indexOf(val.trim()) == -1) {
                        return true;
                    } else {
                        return 'This label already in use in this template.';
                    }
                }
            }, {
                xtype: 'combobox',
                fieldLabel: 'Type',
                name: 'type',
                allowOnlyWhitespace: false,
                editable: false,
                store: 'attributeTypes',
                value: 'brief-text',
                valueField: 'id',
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
                plugins: [rowEditor],
                height: 300,
                store: {
                    data: [],
                    fields: ['name']
                },
                columns: [{
                    text: 'Name',
                    flex: 1,
                    dataIndex: 'name',
                    editor: {
                        xtype: 'textfield'
                    }
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
                            rowEditor.startEdit(rowIndex, colIndex);
                        }
                    }, {
                        icon: '/resources/images/delete.png',
                        tooltip: 'Delete',
                        scope: this,
                        handler: function(grid, rowIndex, colIndex) {
                            rowEditor.cancelEdit();
                            grid.getStore().removeAt(rowIndex);
                        }
                    }]
                }],
                tbar: [{
                    text: 'Add option',
                    icon: '/resources/images/add.png',
                    name: 'addOption',
                    scope: this,
                    handler: function() {
                        rowEditor.cancelEdit();

                        // add item to store
                        this.down('grid').getStore().insert(0, {name: 'New option'});
                        rowEditor.startEdit(0, 0);
                    }
                }]
            }]
        }];


        this.bbar = ['->', {
            text: 'Save',
            icon: '/resources/images/save.png',
            width: 80,
            name: 'save',
            scope: this,
            handler: this.saveAttribute
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

        this.down('form').getForm().setValues(this.attribute.getData());

        var options = this.attribute.get('options') || [];
        if (options.length) {
            var data = [];
            for (var i in options) data.push({name: options[i]});

            this.down('grid').getStore().loadData(data);
        }
    },

    saveAttribute: function() {
        var form = this.down('form').getForm();
        if (!form.isValid())
            return;

        var attribute = form.getValues();
        if (attribute.type == 'option') {
            var storeItems = this.down('grid').getStore().data.items,
                options = [];

            if (storeItems.length == 0) {
                Slims.app.getMainController().error('Validation error.', 'Add at least one option in the table, or select a different type.');
                return;
            }
            for (var i  in storeItems) options.push(storeItems[i].get('name'))

            attribute.options = options;
        }
        if (!this.attribute) {
            this.attribute = Ext.create('Slims.model.sample.Attribute', attribute);
        }
        this.attribute.set(attribute);

        this.fireEvent('save', this.attribute, this, this.grid);
    },

    showOptionsTools: function(options) {
        var optionsPanel = this.down('panel[name=options]');

        optionsPanel.setVisible(true);
    },

    hideOptionsTools: function() {
        var optionsPanel = this.down('panel[name=options]');

        optionsPanel.setVisible(false);
    },

    setWindowTitle: function() {
        var type;
        if (this.grid.name == 'storeAttributes') {
            type = 'Store';
        } else {
            type = 'Remove';
        }

        var title;
        if (this.attribute == null) {
            title = 'Add new attribute to {0} list';
        } else {
            title = 'Edit attribute in {0} list';
        }

        this.title = Ext.String.format(title, type);
    }
});