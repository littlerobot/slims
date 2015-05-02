Ext.define('Slims.view.sample.Page', {
    extend: 'Ext.panel.Panel',
    xtype: 'samplespage',

    layout: 'hbox',
    requires: ['Slims.view.sample.wizard.Wizard'],
    border: false,

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            border: true,
            title: 'Grid',
            flex: 1
        }, {
            xtype: 'panel',
            border: true,
            title: 'Details',
            flex: 2
        }];

        this.tbar = [{
            text: 'Create New Sample',
            handler: this.openWizardTool
        }];

        this.callParent();
    },

    openWizardTool: function() {
        Ext.create('Slims.view.sample.wizard.Wizard').show();
    },

    nextTab: function() {
        this.layout.setActiveItem(this.layout.getNext());
    },

    prevTab: function() {
        this.layout.setActiveItem(this.layout.getPrev());
    },

    commitChanges: function() {

    }
});