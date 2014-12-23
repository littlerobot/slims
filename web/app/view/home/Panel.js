Ext.define('Slims.view.home.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'homepage',

    layout: 'fit',

    requires: [
        'Slims.view.home.Grid',
        'Slims.view.home.container.Window'
    ],

    initComponent: function() {

        this.items = [{
            xtype: 'panel',
            layout: 'hbox',
            defauls: {
            },
            items: [{
                xtype: 'containersgrid',
                height: '100%',
                border: true,
                flex: 1
            }, {
                xtype: 'panel',
                height: '100%',
                border: true,
                html: '<center style="margin-top: 60px;"><h2>Here will be some new content...</h2></center>',
                // flex: 4
                // for debugging
                flex: 1
            }]
        }];

        this.callParent();
    }

});