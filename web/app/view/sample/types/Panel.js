Ext.define('Slims.view.sample.types.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'sampletypespage',

    layout: 'fit',
    border: true,

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            layout: 'vbox',
            items: [{
                xtype: 'panel',
                html: '<center style="padding: 30px;"><h2>Here will be Sample Types grid</h2></center>',
                border: true,
                width: '100%',
                flex: 1
            }]
        }];

        this.callParent();
    }
});