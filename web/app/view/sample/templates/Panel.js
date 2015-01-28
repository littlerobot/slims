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
                xtype: 'templatesgrid',
                width: '100%',
                flex: 1
            }, {
                xtype: 'attributesgrid',
                width: '100%',
                flex: 1
            }]
        }];

        this.callParent();
    }

});