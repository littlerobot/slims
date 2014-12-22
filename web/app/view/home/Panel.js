Ext.define('Slims.view.home.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'homepage',

    layout: 'fit',

    requires: [
        'Slims.view.home.Grid'
    ],

    initComponent: function() {

        this.items = [{
            xtype: 'panel',
            layout: 'hbox',
            defauls: {
            },
            items: [{
                xtype: 'containergrid',
                height: '100%',
                border: true,
                split: true,
                flex: 1
            }, {
                xtype: 'panel',
                height: '100%',
                border: true,
                flex: 2
            }]
        }];

        this.callParent();
    }

});