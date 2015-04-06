Ext.define('Slims.view.sample.templates.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'sampletemplatespage',

    layout: 'fit',
    border: true,

    requires: [
        'Slims.view.sample.templates.TemplatesGrid',
        'Slims.view.sample.templates.AttributesGrid'
    ],

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            layout: 'vbox',
            items: [{
                xtype: 'sampletemplatesgrid',
                width: '100%',
                flex: 1
            }, {
                xtype: 'container',
                layout: 'hbox',
                width: '100%',
                flex: 1,
                items: [{
                    xtype: 'sampleattributesgrid',
                    name: 'storeAttributes',
                    title: 'Store Attributes',
                    flex: 1,
                    height: '100%'
                }, {
                    xtype: 'panel',
                    bodyStyle: 'padding: 5px',
                    border: true,
                    // width: 52,
                    width: 25,
                    height: '100%',
                    layout: {
                        align: 'center',
                        pack: 'middle'
                    },
                    items: [{
                        xtype: 'text',
                        text: "Use drag'n'drop for ordering or moving attributes via grids.",
                        degrees: 90
                    }]
                }, {
                    xtype: 'sampleattributesgrid',
                    name: 'removeAttributes',
                    title: 'Remove Attributes',
                    flex: 1,
                    height: '100%'
                }]
            }]
        }];

        this.callParent();
    }

});