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

    layout: 'border',

    initComponent: function() {
        this.items = [{
            xtype: 'sampletypesgrid',
            region: 'west',
            split: true,
            flex: 1
        }, {
            xtype: 'sampletypeform',
            region: 'center',
            flex: 2
        }];

        this.callParent();
    }
});