Ext.define('Slims.view.home.container.Window', {
    extend: 'Ext.window.Window',
    xtype: 'containerwindow',

    requires: ['Ext.form.Panel'],

    layout: 'hbox',
    width: 800,
    height: 500,

    requires: [
        'Slims.view.home.Grid'
    ],

    initComponent: function() {
        this.title = 'New container';

        this.items = [{
            xtype: 'form',
            border: true,
            bodyPadding: 10,
            flex: 1,
            height: '100%',
            defaults: {
                anchor: '100%'
            },
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Name'
            } ,{
                xtype: 'textfield',
                fieldLabel: 'Stored Inside'
            }]
        }, {
            xtype: 'form',
            border: true,
            bodyPadding: 10,
            flex: 1,
            height: '100%',
            items: []
        }];

        this.bbar = ['->', {
            xtype: 'button',
            name: 'save',
            text: 'Save'
        }, '-', {
            xtype: 'button',
            name: 'cancel',
            text: 'Cancel'
        }]

        this.callParent();
    }

});