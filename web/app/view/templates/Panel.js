Ext.define('Slims.view.templates.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'templatespage',

    layout: 'fit',
    border: true,

    requires: [
        'Slims.view.templates.TemplatesGrid',
        'Slims.view.templates.AttributesGrid'
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