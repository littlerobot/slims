Ext.define('Slims.view.sample.types.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'sampletypespage',

    layout: 'fit',
    border: true,

    requires: ['Ext.draw.Text'],

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            layout: 'hbox',
            items: [{
                xtype: 'panel',
                html: '<center style="padding: 30px;"><h2>Here will be Sample Types grid</h2></center>',
                height: '100%',
                flex: 1
            }, {
                xtype: 'panel',
                html: '<center style="padding: 30px;"><h2>Here will be Sample Type form</h2></center>',
                height: '100%',
                flex: 2
            }]
        }];

        this.callParent();
    }
});