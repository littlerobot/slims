Ext.define('Slims.view.templates.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'templatespage',

    layout: 'fit',

    requires: ['Slims.view.templates.TemplatesGrid'],

    initComponent: function() {

        this.items = [{
            xtype: 'panel',
            layout: 'vbox',
            items: [{
                xtype: 'templatesgrid',
                width: '100%',
                flex: '1'
            }, {
                xtype: 'panel',
                border: true,
                width: '100%',
                flex: '1'
            }]
        }];

        this.callParent();
    }

});