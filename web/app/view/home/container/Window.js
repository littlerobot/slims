Ext.define('Slims.view.home.container.Window', {
    extend: 'Ext.panel.Panel',
    xtype: 'containerwindow',

    layout: 'fit',

    requires: [
        'Slims.view.home.Grid'
    ],

    initComponent: function() {
        this.title = 'New container';

        this.items = [{
            xtype: 'form',
        }];

        this.callParent();
    }

});