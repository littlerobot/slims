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
        'Ext.tree.Panel',
        'Slims.ux.ColorButton'
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
                    allowOnlyWhitespace: false,
                    width: '100%',
                    labelWidth: 20,
                    fieldLabel: 'Name'
                }, {
                    xtype: 'radiogroup',
                    name: 'holds_other_containers',
                    width: '100%',
                    layout: 'vbox',
                    items: [{
                        boxLabel: 'Only holds other containers',
                        name: 'holds_other_containers',
                        inputValue: false
                    }, {
                        boxLabel: 'Stored inside',
                        name: 'holds_other_containers',
                        inputValue: true,
                        listeners: {
                            change: function(radio, newValue) {
                                this.down('treepanel').setDisabled(!newValue);
                            },
                            scope: this
                        }
                    }]
                }, {
                    xtype: 'treepanel',
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
                }, {
                    xtype: 'colorbutton'
                }, {
                    xtype: 'textarea',
                    name: 'comment',
                    width: '100%',
                    fieldLabel: 'Comment',
                    labelAlign: 'top',
                    height: 150
                }, {
                    xtype: 'numberfield',
                    name: 'number',
                    hidden: true,
                    disabled: this.isEditMode(),
                    fieldLabel: 'Number of containers to create',
                    labelWidth: 190,
                    width: 250,
                    allowOnlyWhitespace: false,
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
            style: 'margin-top: 10px;',
            layout: 'vbox',
            width: '100%',
            labelAlign: 'top',
            fieldLabel: 'Belongs to',
            items: [{
                boxLabel: 'Nobody',
                name: 'belongs_to',
                inputValue: 'no',
                checked: true,
                listeners: {
                    change: function(radio, newValue) {
                        this.down('combobox[name=research_group]').setDisabled(newValue);
                    },
                    scope: this
                }
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
                    disabled: true,
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
                maxValue: 100,
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
                maxValue: 100,
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
            colour: this.down('colorbutton').getValue(),
            parent: parentId,
            owner: research_group || null
        });

        var container = this.record;

        // edit or add mode
        if (container) {
            container.set(values);
        } else {
            container = Ext.create('Slims.model.Container', values);
        }

        if (container.data.parent) {
            this.setParentPath(container);
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

        this.down('colorbutton').setValue(container.colour);
        this.setParentContainer(container.parentId);

        this.down('form').getForm().setValues(container);

    },

    setParentPath: function(container) {
        if (this.isEditMode()) {
            container.set('parentPath', container.getPath());
        } else {
            // get path from selected tree record
            container.set('parentPath', this.down('treepanel').selModel.selected.get(0).getPath());
        }
    },

    setParentContainer: function(parentId) {
        var holdsOtherContainers = parentId !== 'root';

        this.down('radiogroup[name=holds_other_containers]').setValue({holds_other_containers: holdsOtherContainers});

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
