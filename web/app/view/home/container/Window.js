Ext.define('Slims.view.home.container.Window', {
    extend: 'Ext.window.Window',
    xtype: 'containerwindow',

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

    layout: 'fit',
    width: 800,
    height: 500,
    resizable: false,
    modal: true,
    record: null,

    initComponent: function() {
        if (this.isEditMode()) {
            this.title = Ext.String.format('Edit "{0}" container',this.record.get('name'));
        } else {
            this.title = 'New container';
        }

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
                    name: 'name',
                    allowBlank: false,
                    width: '100%',
                    labelWidth: 20,
                    fieldLabel: 'Name'
                }, {
                    xtype: 'radiogroup',
                    disabled: this.isEditMode(),
                    name: 'holds_other_containers',
                    layout: 'vbox',
                    items: [{
                        boxLabel: 'Only holds other containers',
                        name: 'holds_other_containers',
                        inputValue: false,
                        checked: true
                    }, {
                        boxLabel: 'Stored inside',
                        name: 'holds_other_containers',
                        inputValue: true
                    }]
                }, {
                    xtype: 'treepanel',
                    disabled: this.isEditMode(),
                    name: 'storesInside',
                    style: 'padding-left: 20px;',
                    border: true,
                    height: 150,
                    width: '100%',
                    displayField: 'name',
                    store: Ext.create('Slims.store.Containers'),
                    rootVisible: false
                },
                    this.getResearchGroupRadioCmp(),
                    this.getDimensionsCmp(),
                    this.getDimensionsTotalCmp()
                ]
            }, {
                xtype: 'panel',
                bodyPadding: 10,
                flex: 1,
                height: '100%',
                items: [{
                    xtype: 'radiogroup',
                    disabled: this.isEditMode(),
                    name: 'stores',
                    layout: 'vbox',
                    width: '100%',
                    labelAlign: 'top',
                    fieldLabel: 'Stores',
                    items: [{
                        boxLabel: 'Samples',
                        name: 'stores',
                        inputValue: 'samples',
                        checked: true
                    }, {
                        boxLabel: 'Other containers',
                        name: 'stores',
                        inputValue: 'containers'
                    }]
                },
                    this.getColorPickerCmp(),
                {
                    xtype: 'textarea',
                    name: 'comment',
                    width: '100%',
                    fieldLabel: 'Comment',
                    labelAlign: 'top',
                    height: 150
                }, {
                    xtype: 'numberfield',
                    name: 'number',
                    disabled: this.isEditMode(),
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

        this.bbar = this.getBbarCmpArray();

        this.on('afterrender', this.setupData, this);

        this.callParent();
    },

    getResearchGroupRadioCmp: function() {
        return {
            xtype: 'radiogroup',
            name: 'belongs_to',
            disabled: this.isEditMode(),
            style: 'margin-top: 10px;',
            layout: 'vbox',
            width: '100%',
            labelAlign: 'top',
            fieldLabel: 'Belongs to',
            items: [{
                boxLabel: 'Nobody',
                name: 'belongs_to',
                inputValue: 'no',
                checked: true
            }, {
                xtype: 'fieldcontainer',
                width: '100%',
                layout: 'hbox',
                items: [{
                    xtype: 'radiofield',
                    name: 'belongs_to',
                    inputValue: 'group'
                }, {
                    xtype: 'combobox',
                    name: 'research_group',
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
        };
    },

    getDimensionsCmp: function() {
        var updateTotal = function(cmp, val) {
            var rows = this.down('numberfield[name=rows]').getValue() || 1;
            var columns = this.down('numberfield[name=columns]').getValue() || 1;

            this.down('label[name=totalCapacity]').setText(rows*columns);
        };


        return {
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
                name: 'rows',
                disabled: this.isEditMode(),
                minValue: 1,
                value: 1,
                listeners: {
                    change: updateTotal,
                    scope: this
                },
                flex: 1
            }, {
                xtype: 'label',
                style: 'margin-left: 4px;',
                width: 50,
                text: 'rows by'
            }, {
                xtype: 'numberfield',
                disabled: this.isEditMode(),
                name: 'columns',
                value: 1,
                minValue: 1,
                flex: 1,
                listeners: {
                    change: updateTotal,
                    scope: this
                },
                labelAlign: 'right'
            }, {
                xtype: 'label',
                style: 'margin-left: 4px;',
                width: 50,
                text: 'columns'
            }]
        };
    },

    getDimensionsTotalCmp: function() {
        return {
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
                text: '1',
                style: 'margin-left: 4px;',
                name: 'totalCapacity'
            }]
        };
    },

    getColorPickerCmp: function() {
        return {
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
                    name: 'colour',
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
        };
    },

    getBbarCmpArray: function() {
        return [
            '->', {
            text: 'Save',
            icon: '/resources/images/save.png',
            width: 80,
            name: 'save',
            scope: this,
            handler: this.saveContainer
        }, {
            text: 'Cancel',
            icon: '/resources/images/cancel.png',
            width: 80,
            scope: this,
            handler: this.close
        }];
    },

    saveContainer: function() {
        var formPanel = this.down('form');

        if (!formPanel.getForm().isValid())
            return;


        // check research group selection
        if (this.down('radiogroup[name=belongs_to]').getValue().belongs_to == 'group') {
            var research_group = this.down('combobox[name=research_group]').getValue();
            if (!research_group) {
                Ext.Msg.alert('Select research group', 'Please, select research group before saving.');
                return;
            }
        }

        var values = formPanel.getForm().getValues();

        var parentId = this.getParentContainerId();

        // return if error
        if (parentId === false)
            return;

            Ext.apply(values, {
                colour: '#' + this.down('colorpicker').getValue(),
                parent: parentId,
                owner: research_group || null
            });

        var container = this.record;

        // TODO: Add data preparation here

        // edit or add mode
        if (container) {
            container.set(values);
        } else {
            container = Ext.create('Slims.model.Container', values);
        }

        this.fireEvent('save', container, this);
    },

    setupData: function() {
        if (!this.record)
            return;

        var container = this.record.getData();

        if (container.research_group) {
            this.down('radiogroup[name=belongs_to]').setValue({belongs_to: 'group'});
            container.research_group = container.research_group.id;
        }

        this.setColor(container.colour);
        this.setParentContainer(container.parentId);

        this.down('form').getForm().setValues(container);

    },

    setColor: function(color) {
        if (!color)
            return;

        this.down('container[name=colorPalette]').el.setStyle('background', color);
        color = color.replace('#', '');
        var picker = this.down('colorpicker');
        // add color if it isn't in palette yet for resolving exception
        if (picker.colors.indexOf(color) == -1) {
            picker.colors.push(color);
        }

        picker.select(color);
    },

    setParentContainer: function(parentId) {
        if (!parentId)
            return;

        this.down('radiogroup[name=holds_other_containers]').setValue({holds_other_containers: true});

        var treePanel = this.down('treepanel');
        this.down('treepanel').expandPath(this.record.getPath(), 'id', '/', function() { treePanel.selModel.select(treePanel.store.getNodeById(parentId)); });
    },

    getParentContainerId: function() {
        if (this.down('radiogroup[name=holds_other_containers]').getValue().holds_other_containers != '1')
            return null;

        var container = this.down('treepanel').selModel.selected.get(0);
        if (!container) {
            Ext.Msg.alert('Select parent container', 'Parent container for "Stored Inside" field not selected.');
            return false;
        }

        return container.get('id');
    },

    isEditMode: function() {
        return !!this.record;
    }
});