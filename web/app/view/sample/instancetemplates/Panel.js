Ext.define('Slims.view.sample.instancetemplates.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'instancetemplatespage',

    layout: 'fit',
    border: true,

    requires: [
        'Slims.view.sample.instancetemplates.TemplatesGrid',
        'Slims.view.sample.instancetemplates.AttributesGrid'
    ],

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            layout: 'vbox',
            items: [{
                xtype: 'instancetemplatesgrid',
                width: '100%',
                flex: 1
            }, {
                xtype: 'container',
                layout: 'hbox',
                width: '100%',
                flex: 1,
                items: [{
                    xtype: 'instancetemplatesattributes',
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
                        text: 'Drag attributes to change their order',
                        degrees: 90
                    }]
                }, {
                    xtype: 'instancetemplatesattributes',
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