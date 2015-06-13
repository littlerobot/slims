Ext.define('Slims.view.home.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'homepage',

    layout: 'fit',

    requires: [
        'Slims.view.home.Grid',
        'Slims.view.home.container.Window',
        'Ext.tree.Panel'
    ],

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            layout: 'hbox',
            items: [{
                xtype: 'containersgrid',
                border: true,
                height: '100%',
                flex: 1
            }, {
                xtype: 'panel',
                name: 'details',
                layout: 'vbox',
                height: '100%',
                flex: 1,
                items: [{
                    xtype: 'positionsview',
                    width: '100%',
                    flex: 3
                }, {
                    xtype: 'positionsgrid',
                    width: '100%',
                    border: true,
                    flex: 2
                }],
                buttons: ['->', {
                    text: 'Store Samples',
                    name: 'storeSamples'
                }]
            }]
        }];

        this.callParent();
    }
});