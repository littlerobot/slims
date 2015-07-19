Ext.define('Slims.view.sample.Page', {
    extend: 'Ext.panel.Panel',
    xtype: 'samplespage',

    layout: 'card',

    initComponent: function() {
        this.tbar = [{
            xtype: 'panel',
            width: '100%',
            layout: {
                type: 'hbox',
                pack: 'center'
            },
            defaults: {
                width: 170,
                margin: '5 5 5 0',
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
                text: 'Search'
            }, {
                xtype: 'button',
                index: 1,
                text: 'Type Templates'
            }, {
                xtype: 'button',
                index: 2,
                width: 200,
                text: 'Instance Templates'
            }, {
                xtype: 'button',
                index: 3,
                text: 'Types'
            }]
        }];

        this.items = [{
            xtype: 'panel'
        }, {
            xtype: 'templatespage'
        }, {
            xtype: 'instancetemplatespage',
            border: true,
            padding: '0 5 5 5'
        }, {
            xtype: 'sampletypespage',
            border: true,
            padding: '0 5 5 5'
        }]

        this.callParent();
    },

    changeTab: function(btn) {
        this.getLayout().setActiveItem(btn.index);
    }
});