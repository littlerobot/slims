Ext.define('Slims.view.sample.Page', {
    extend: 'Ext.panel.Panel',
    xtype: 'samplespage',

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
                scale: 'medium', // for setting font bigger
                allowDepress: false,
                enableToggle: true,
                toggleGroup: 'tabs',
                handler: this.changeTab,
                scope: this
            },
            items: [{
                xtype: 'button',
                pressed: true,
                index: 0,
                text: 'Type Templates'
            }, {
                xtype: 'button',
                index: 1,
                width: 200,
                text: 'Instance Templates'
            }, {
                xtype: 'button',
                index: 2,
                text: 'Types'
            }]
        }];

        this.items = [{
            xtype: 'templatespage',
            border: true
        }, {
            xtype: 'sampletemplatespage',
            border: true
        }, {
            xtype: 'sampletypespage',
            border: true
        }]

        this.callParent();
    },

    changeTab: function(btn) {
        this.getLayout().setActiveItem(btn.index);
    }
});