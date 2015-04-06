Ext.define('Slims.view.sample.types.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'sampletypespage',

    layout: 'fit',
    border: false,

    requires: [
        'Ext.draw.Text',
        'Slims.view.sample.types.Grid',
        'Slims.view.sample.types.Form',
        'Slims.view.sample.types.Window'
    ],

    initComponent: function() {
        this.items = [{
            xtype: 'sampletypesgrid'
        }];

        this.callParent();
    }
});