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
            items: [{
                xtype: 'panel',
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
                bodyStyle: 'padding: 5px;',
                border: true,
                items: [{
                    xtype: 'button',
                    pressed: true,
                    index: 0,
                    text: 'Search'
                }]
            }, {
                xtype: 'panel',
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
                bodyStyle: 'padding: 5px;',
                border: true,
                style: 'padding-left: 5px;',
                items: [{
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
            }]
        }];

        this.items = [{
            xtype: 'panel',
            layout: 'vbox',
            border: true,
            padding: '0 5 5 5',
            items: [{
                xtype: 'samplessearchfilter',
                width: '100%'
            }, {
                xtype: 'samplesearch',
                width: '100%',
                flex: 1
            }]
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