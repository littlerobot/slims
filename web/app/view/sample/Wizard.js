Ext.define('Slims.view.sample.Wizard', {
    extend: 'Ext.window.Window',
    xtype: 'samplewizard',

    layout: 'fit',
    border: false,
    width: 600,
    height: 500,

    title: 'Create Sample Wizard Tool',
    requires: [],

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            name: 'cardPanel',
            layout: 'card',
            items: [{
                xtype: 'form',
                layout: 'fit',
                items: [{
                    xtype: 'label',
                    padding: 10,
                    width: '100%',
                    html: '<center><h2>Welcome to Create New Sample Wizard.</h2></center>'
                }, {
                    xtype: 'grid',
                    tbar: [{
                        xtype: 'combo',
                        padding: 10,
                        name: 'templateId',
                        fieldLabel: 'Please, select <i>Sample Type</i> for a new sample and press <i>Next</i>',
                        labelAlign: 'top',
                        emptyText: 'Select to continue',
                        labelWidth: 180,
                        allowBlank: false,
                        editable: false,
                        store: Ext.StoreMgr.get('templates'),
                        queryMode: 'local',
                        displayField: 'name',
                        valueField: 'id',
                        listeners: {
                            change: function(combo, value) {
                                var template = combo.store.findRecord(combo.valueField, value),
                                    attributes = template.get('attributes');
                                this.down('grid').getStore().loadData(attributes);
                            },
                            scope: this
                        }
                    }],
                    store: {
                        fields: ['order', 'label', 'type'],
                        data: []
                    },
                    columns: {
                        defaults: {
                            menuDisabled: true,
                            resizable: false,
                            sortable: false,
                            draggable: false
                        },
                        items: [{
                            dataIndex: 'order',
                            width: 40,
                            header: '#'
                        }, {
                            dataIndex: 'label',
                            flex: 1,
                            header: 'Label'
                        }, {
                            dataIndex: 'type',
                            width: 200,
                            header: 'Type'
                        }]
                    }
                }],
                buttons: ['->', {
                    text: 'Next',
                    handler: function() {
                        var valid = this.down('panel[name=cardPanel]').layout.getActiveItem().form.isValid();
                        if (valid) {
                            this.nextTab();
                        }
                    },
                    scope: this
                }]
            }, {
                xtype: 'panel',
                bodyStyle: 'background: green;',
                buttons: [{
                    text: 'Prev',
                    handler: this.prevTab,
                    scope: this
                }, '->', {
                    text: 'Next',
                    handler: this.nextTab,
                    scope: this
                }]
            }, {
                xtype: 'panel',
                bodyStyle: 'background: red;',
                buttons: [{
                    text: 'Prev',
                    handler: this.prevTab,
                    scope: this
                }, '->', {
                    text: 'Finish',
                    handler: this.commitChanges,
                    scope: this
                }]
            }]
        }];

        this.callParent();
    },

    nextTab: function() {
        var cardPanel = this.down('panel[name=cardPanel]');
        cardPanel.layout.setActiveItem(cardPanel.layout.getNext());
    },

    prevTab: function() {
        var cardPanel = this.down('panel[name=cardPanel]');
        cardPanel.layout.setActiveItem(cardPanel.layout.getPrev());
    },

    commitChanges: function() {

    }
});