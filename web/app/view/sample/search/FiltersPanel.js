Ext.define('Slims.view.sample.search.FiltersPanel', {
    extend: 'Ext.panel.Panel',

    xtype: 'samplessearchfilter',

    width: '100%',
    // height: 40,
    layout: 'vbox',
    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            width: '100%',
            layout: 'hbox',
            tbar: [{
                xtype: 'textfield',
                name: 'searchQuery',
                fieldLabel: 'Search',
                labelWidth: 50,
                width: 350
            }, {
                xtype: 'button',
                name: 'searchBtn',
                // icon: '/resources/images/search.png',
                text: 'Search'
            }, {
                xtype: 'container',
                flex: 1
            }, {
                xtype: 'button',
                text: 'Advanced',
                handler: this.triggerAdvancedFilterPanel,
                scope: this
            }]
        }, {
            xtype: 'form',
            width: '100%',
            border: true,
            hidden: true,
            layout: 'hbox',
            padding: 5,
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
            }]
        }];

        this.callParent();
    },

    triggerAdvancedFilterPanel: function() {
        advancedFilterPanel = this.down('[name=advancedFilter]');

        if (!advancedFilterPanel.isVisible()) {
            advancedFilterPanel.show();
        } else {
            advancedFilterPanel.hide();
        }
    }
});