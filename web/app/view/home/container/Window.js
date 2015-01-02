Ext.define('Slims.view.home.container.Window', {
    extend: 'Ext.window.Window',
    xtype: 'containerwindow',

    layout: 'fit',
    width: 800,
    height: 500,
    resizable: false,

    requires: [
        'Ext.form.field.Checkbox',
        'Ext.form.RadioGroup',
        'Ext.form.field.Number',
        'Ext.form.field.Radio',
        'Ext.form.Panel',
        'Ext.form.field.ComboBox',
        'Ext.form.Label',
        'Ext.picker.Color',
        'Ext.tree.Panel'
    ],

    initComponent: function() {
        this.title = 'New container';

        this.items = [{
            xtype: 'form',
            layout: 'hbox',
            items: [{
                xtype: 'panel',
                bodyPadding: 10,
                flex: 1,
                height: '100%',
                items: [{
                    xtype: 'textfield',
                    allowBlank: false,
                    width: '100%',
                    labelWidth: 20,
                    fieldLabel: 'Name'
                }, {
                    xtype: 'radiogroup',
                    layout: 'vbox',
                    items: [{
                        boxLabel: 'Only holds other containers',
                        name: 'holds_other_containers',
                        inputValue: '2',
                        checked: true
                    }, {
                        boxLabel: 'Stored inside',
                        name: 'holds_other_containers',
                        inputValue: '1'
                    }]
                }, {
                    xtype: 'treepanel',
                    style: 'margin-left: 20px;',
                    border: true,
                    height: 150,
                    width: 300,
                    displayField: 'name',
                    store: Ext.create('Slims.store.Containers'),
                    rootVisible: false
                }, {
                    xtype: 'radiogroup',
                    style: 'margin-top: 10px;',
                    layout: 'vbox',
                    width: '100%',
                    labelAlign: 'top',
                    fieldLabel: 'Belongs to',
                    items: [{
                        boxLabel: 'Nobody',
                        name: 'belongs_to',
                        inputValue: '',
                        checked: true
                    }, {
                        xtype: 'fieldcontainer',
                        width: '100%',
                        layout: 'hbox',
                        items: [{
                            xtype: 'radiofield',
                            name: 'belongs_to',
                            inputValue: '3'
                        }, {
                            xtype: 'combobox',
                            style: 'margin-left: 5px;',
                            flex: 1,
                            emptyText: 'Select research group',
                            editable: false,
                            store: Ext.StoreMgr.get('researchGroups'),
                            queryMode: 'local',
                            displayField: 'name',
                            valueField: 'id'
                        }]
                    }]
                }, {
                    xtype: 'fieldcontainer',
                    layout: {
                        type: 'hbox',
                        align: 'middle'
                    },
                    style: 'margin-top: 10px;',
                    width: '100%',
                    labelWidth: 130,
                    fieldLabel: 'Dimensions/capacity',
                    items: [{
                        xtype: 'numberfield',
                        name: 'rowsCount',
                        minValue: 0,
                        hideAlign: true,
                        flex: 1
                    }, {
                        xtype: 'label',
                        style: 'margin-left: 4px;',
                        width: 50,
                        text: 'rows by'
                    }, {
                        xtype: 'numberfield',
                        name: 'columnsCount',
                        minValue: 0,
                        hideAlign: true,
                        flex: 1,
                        labelAlign: 'right'
                    }, {
                        xtype: 'label',
                        style: 'margin-left: 4px;',
                        width: 50,
                        text: 'columns'
                    }]
                }, {
                    xtype: 'fieldcontainer',
                    width: '100%',
                    style: 'margin-top: 10px;',
                    layout: {
                        type: 'hbox',
                        pack: 'end'
                    },
                    items: [{
                        xtype: 'label',
                        text: 'Total capacity:'
                    }, {
                        xtype: 'label',
                        text: '0',
                        style: 'margin-left: 4px;',
                        name: 'totalCapacity'
                    }]
                }]
            }, {
                xtype: 'panel',
                bodyPadding: 10,
                flex: 1,
                height: '100%',
                items: [{
                    xtype: 'radiogroup',
                    layout: 'vbox',
                    width: '100%',
                    labelAlign: 'top',
                    fieldLabel: 'Stores',
                    items: [{
                        boxLabel: 'Samples',
                        name: 'stores',
                        inputValue: '1',
                        checked: true
                    }, {
                        boxLabel: 'Other containers',
                        name: 'stores',
                        inputValue: '2'
                    }]
                },{
                    xtype: 'container',
                    layout: {
                        type: 'hbox',
                        align: 'middle'
                    },
                    items: [{
                        xtype: 'panel',
                        border: true,
                        layout: 'fit',
                        height: 24,
                        width: 24,
                        items: [{
                            xtype: 'container',
                            name: 'colorPalette',
                            listeners: {
                                afterrender: function(container) {
                                    container.el.dom.removeChild(container.el.dom.childNodes[0]);
                                }
                            }
                        }]
                    }, {
                        xtype: 'button',
                        style: 'margin-left: 8px;',
                        text: 'Select color',
                        width: 110,
                        name: 'colorButton',
                        menu: [{
                            xtype: 'colorpicker',
                            value: 'FFFFFF',
                            listeners: {
                                scope: this,
                                select: function(picker, selColor) {
                                    picker.up('button[name=colorButton]').menu.hide();
                                    this.down('container[name=colorPalette]').el.setStyle('background', '#'+selColor);
                                }
                            }
                        }]
                    }]
                }, {
                    xtype: 'textarea',
                    width: '100%',
                    fieldLabel: 'Comment',
                    labelAlign: 'top',
                    height: 150
                }, {
                    xtype: 'numberfield',
                    fieldLabel: 'Number of containers to create',
                    labelWidth: 190,
                    width: 250,
                    allowBlank: false,
                    maxValue: 1,
                    minValue: 1,
                    value: 1
                }]
            }]
        }];

        this.bbar = [
            '->', {
            text: 'Save',
            icon: '/resources/images/save.png',
            name: 'save',
            scope: this,
            handler: function() {

                if (!this.down('form').getForm().isValid())
                    return;

                var formValues = this.down('form').getForm().getValues();

                // TODO: Add data preparation here
                var container = this.record;
                // check edit or add mode
                if (container) {
                    container.set(formValues);
                } else {
                    container = Ext.create('Slims.model.Container', formValues);
                }

                this.fireEvent('save', container, this);
            }
        }, '-', {
            text: 'Cancel',
            icon: '/resources/images/cancel.png',
            scope: this,
            handler: this.close
        }];

        this.callParent();
    }
});