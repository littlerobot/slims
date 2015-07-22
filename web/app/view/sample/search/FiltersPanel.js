Ext.define('Slims.view.sample.search.FiltersPanel', {
    extend: 'Ext.panel.Panel',

    xtype: 'samplessearchfilter',

    width: '100%',
    // height: 40,
    layout: 'vbox',
    initComponent: function() {
        this.items = [{
            xtype: 'form',
            width: '100%',
            layout: 'hbox',
            bodyPadding: 5,
            name: 'advancedFilter',
            items: [{
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
                    name: 'sampleName'
                }, {
                    xtype: 'textfield',
                    fieldLabel: 'User Name',
                    name: 'userName'
                }, {
                    xtype: 'datefield',
                    name: 'storedFrom',
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
                    name: 'sampleType'
                }, {
                    xtype: 'textfield',
                    fieldLabel: 'Container Name',
                    name: 'containerName'
                }, {
                    xtype: 'datefield',
                    name: 'storedTill',
                    fieldLabel: 'Date stored till'
                }]
            }],
            buttons: [{
                text: 'Search',
                name: 'search'
            }]
        }];

        this.callParent();
    }
});