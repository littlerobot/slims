Ext.define('Slims.view.sample.types.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'sampletypespage',

    layout: 'fit',
    border: true,

    requires: [
        'Ext.draw.Text',
        'Slims.view.sample.types.Grid',
        'Slims.view.sample.types.Form'
    ],

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            layout: 'hbox',
            items: [{
                xtype: 'sampletypesgrid',
                height: '100%',
                flex: 1
            }, {
                xtype: 'sampletypeform',
                height: '100%',
                flex: 2
            }]
        }];

        this.callParent();
    }
});