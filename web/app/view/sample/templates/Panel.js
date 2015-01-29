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
                    title: 'On store Attributes',
                    flex: 1,
                    height: '100%'
                }, {
                    xtype: 'panel',
                    bodyStyle: 'padding: 5px',
                    border: true,
                    width: 55,
                    height: '100%',
                    layout: {
                        type: 'vbox',
                        pack: 'center'
                    },
                    items: [{
                        xtype: 'button',
                        text: '>>',
                        width: 40
                    }, {
                        xtype: 'button',
                        text: '<=>',
                        style: 'margin-top: 10px; margin-bottom: 10px;',
                        width: 40
                    }, {
                        xtype: 'button',
                        text: '<<',
                        width: 40
                    }]
                }, {
                    xtype: 'sampleattributesgrid',
                    title: 'On Remove Attributes',
                    flex: 1,
                    height: '100%'
                }]
            }]
        }];

        this.callParent();
    }

});