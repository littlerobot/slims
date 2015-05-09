Ext.define('Slims.view.sample.Page', {
    extend: 'Ext.panel.Panel',
    xtype: 'samplespage',

    requires: ['Slims.view.sample.wizard.Wizard'],

    layout: 'card',
    border: false,

    initComponent: function() {
        this.tbar = [{
            xtype: 'panel',
            border: true,
            width: '100%',
            padding: 10,
            layout: 'hbox',
            defaults: {
                width: 170,
                margin: 10,
                index: 0,
                scale: 'medium',
                enableToggle: true,
                toggleGroup: 'tabs',
                handler: this.changeTab,
                scope: this
            },
            items: [{
                xtype: 'button',
                index: 0,
                text: 'Samples'
            }, {
                xtype: 'button',
                index: 1,
                text: 'Sample Types'
            }, {
                xtype: 'button',
                index: 2,
                text: 'Instance Templates'
            }, {
                xtype: 'button',
                index: 3,
                text: 'Type Templates'
            }]
        }];

        this.items = [{
            xtype: 'panel',
            border: true,
            tbar: [{
                text: 'Create New Sample',
                handler: this.openWizardTool
            }]
        }, {
            xtype: 'sampletypespage',
            border: true
        }, {
            xtype: 'sampletemplatespage',
            border: true
        }, {
            xtype: 'templatespage',
            border: true
        }]

        this.callParent();
    },

    openWizardTool: function() {
        Ext.create('Slims.view.sample.wizard.Wizard').show();
    },

    commitChanges: function() {

    },
    changeTab: function(btn) {
        this.getLayout().setActiveItem(btn.index);
    }
});