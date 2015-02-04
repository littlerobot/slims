Ext.define('Slims.view.sample.templates.Panel', {
    extend: 'Ext.panel.Panel',
    xtype: 'sampletemplatespage',

    layout: 'fit',
    border: true,

    requires: [
        'Slims.view.sample.templates.TemplatesGrid',
        'Slims.view.sample.templates.AttributesGrid'
    ],

    initComponent: function() {

        this.items = [{
            xtype: 'panel',
            layout: 'vbox',
            items: [{
                xtype: 'sampletemplatesgrid',
                width: '100%',
                flex: 1
            }, {
                xtype: 'container',
                layout: 'hbox',
                width: '100%',
                flex: 1,
                items: [{
                    xtype: 'sampleattributesgrid',
                    name: 'storeAttributes',
                    title: 'Store Attributes',
                    flex: 1,
                    height: '100%'
                }, {
                    xtype: 'panel',
                    bodyStyle: 'padding: 5px',
                    border: true,
                    width: 52,
                    height: '100%',
                    layout: 'vbox',
                    items: [{
                        xtype: 'button',
                        icon: '/resources/images/copy_attr.png',
                        iconAlign: 'center',
                        scale: 'large',
                        tooltip: 'Copy attribute in both lists',
                        iconCls: 'button-icon-aligncenter',
                        width: 40
                    }, {
                        xtype: 'button',
                        icon: '/resources/images/put_right.png',
                        iconAlign: 'center',
                        scale: 'large',
                        tooltip: 'Move attribute to Remove list',
                        style: 'margin-top: 10px; margin-bottom: 10px;',
                        iconCls: 'button-icon-aligncenter',
                        width: 40
                    }, {
                        xtype: 'button',
                        icon: '/resources/images/put_left.png',
                        iconAlign: 'center',
                        scale: 'large',
                        tooltip: 'Move attribute to Store list',
                        iconCls: 'button-icon-aligncenter',
                        width: 40
                    }]
                }, {
                    xtype: 'sampleattributesgrid',
                    name: 'removeAttributes',
                    title: 'Remove Attributes',
                    flex: 1,
                    height: '100%'
                }]
            }]
        }];

        this.callParent();
    }

});