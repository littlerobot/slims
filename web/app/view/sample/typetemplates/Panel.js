Ext.define('Slims.view.sample.typetemplates.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'templatespage',

    layout: 'fit',

    requires: [
        'Slims.view.sample.typetemplates.TemplatesGrid',
        'Slims.view.sample.typetemplates.AttributesGrid'
    ],

    initComponent: function() {

        this.items = [{
            xtype: 'panel',
            layout: 'vbox',
            items: [{
                xtype: 'templatesgrid',
                width: '100%',
                border: true,
                padding: '0 5 5 5',
                flex: 1
            }, {
                xtype: 'attributesgrid',
                width: '100%',
                border: true,
                padding: '0 5 5 5',
                flex: 1
            }]
        }];

        this.callParent();
    }

});