Ext.define('Slims.view.sample.search.FiltersForm', {
    extend: 'Ext.form.Panel',
    xtype: 'samplessearchfilter',

    width: '100%',
    layout: 'hbox',
    bodyPadding: 5,

    initComponent: function() {
        this.items = [{
            xtype: 'container',
            defaults: {
                padding: 5,
                labelWidth: 200,
                labelAlign: 'right',
                width: '100%'
            },
            flex: 1,
            layout: 'vbox',
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Sample Name',
                name: 'name'
            }, {
                xtype: 'textfield',
                fieldLabel: 'User Name',
                name: 'user'
            }, {
                xtype: 'datefield',
                name: 'stored_start',
                fieldLabel: 'Date stored from'
            }]
        }, {
            xtype: 'container',
            defaults: {
                padding: 5,
                labelWidth: 200,
                labelAlign: 'right',
                width: '100%'
            },
            flex: 1,
            layout: 'vbox',
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Sample Type',
                name: 'type'
            }, {
                xtype: 'textfield',
                fieldLabel: 'Container Name',
                name: 'container'
            }, {
                xtype: 'datefield',
                name: 'stored_end',
                fieldLabel: 'Date stored till'
            }]
        }];

        this.buttons = [{
            text: 'Search',
            name: 'search'
        }]

        this.callParent();
    }
});