Ext.define('Slims.view.home.container.Window', {
    extend: 'Ext.window.Window',
    xtype: 'containerwindow',

    requires: ['Ext.form.Panel'],

    layout: 'hbox',
    width: 800,
    height: 500,

    requires: [
        'Ext.form.field.Checkbox',
        'Ext.form.RadioGroup',
        'Ext.form.field.Radio'
    ],

    initComponent: function() {
        this.title = 'New container';

        this.items = [{
            xtype: 'form',
            border: true,
            bodyPadding: 10,
            flex: 1,
            height: '100%',
            defaults: {
                anchor: '100%',
                labelWidth: 60
            },
            items: [{
                xtype: 'textfield',
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
                xtype: 'panel',
                html: '<center style="margin: 40px;"><h4>Containers tree</h4></center>',
                border: true,
                height: 150,
                width: 300,
                style: 'margin-left: 20px;'
            }, {
                xtype: 'radiogroup',
                style: 'margin-top: 10px;',
                layout: 'vbox',
                labelAlign: 'top',
                boxLabel: 'Belongs to',
                items: [{
                    boxLabel: 'Nobody',
                    name: 'belongs_to',
                    inputValue: '',
                    checked: true
                }, {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    items: [{
                        xtype: 'radiofield',
                        name: 'belongs_to',
                        inputValue: '3'
                    }, {
                        xtype: 'combobox',
                        style: 'margin-left: 5px;',
                        flex: 1,
                        editable: false,
                        store: Ext.StoreMgr.get('researchGroups'),
                        queryMode: 'local',
                        displayField: 'name',
                        valueField: 'id'
                    }]
                }]
            }, {
                xtype: 'fieldcontainer',
                layout: 'hbox',
                style: 'margin-top: 10px;',
                width: '100%',
                labelWidth: 130,
                fieldLabel: 'Dimensions/capacity',
                items: [{
                    xtype: 'textfield',
                    hideAlign: true,
                    flex: 1
                }, {
                    xtype: 'label',
                    style: 'margin-top: 4px; margin-left: 4px;',
                    width: 50,
                    text: 'rows by'
                }, {
                    xtype: 'textfield',
                    hideAlign: true,
                    flex: 1,
                    labelAlign: 'right'
                }, {
                    xtype: 'label',
                    style: 'margin-top: 4px; margin-left: 4px;',
                    width: 50,
                    text: 'columns'
                }]
            }]
        }, {
            xtype: 'form',
            border: true,
            bodyPadding: 10,
            flex: 1,
            height: '100%',
            items: []
        }];

        this.bbar = ['->', {
            xtype: 'button',
            name: 'save',
            text: 'Save'
        }, '-', {
            xtype: 'button',
            name: 'cancel',
            text: 'Cancel'
        }];

        this.callParent();
    }

});