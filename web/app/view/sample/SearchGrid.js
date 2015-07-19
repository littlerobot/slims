Ext.define('Slims.view.sample.SearchGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'samplesearch',

    initComponent: function() {
        this.store = Ext.create('Ext.data.Store', {
            fields: [
                'container',
                'containerName',
                'row',
                'column',
                'colour',
                'sampleType',
                'sampleTypeName',
                'name',
                'sampleTemplate',
                'sampleTemplateName',
                {
                    name: 'attributes',
                    type: 'auto'
                }
            ]
        });

        this.tbar = [{
            xtype: 'textfield',
            name: 'searchQuery',
            fieldLabel: 'Search',
            labelWidth: 50,
            width: 350
        }, {
            xtype: 'button',
            text: 'Search',
            name: 'searchBtn'
        }, '->', {
            xtype: 'button',
            text: 'Advanced'
        }];

        this.columns = [{
            dataIndex: 'containerName',
            header: 'Container',
            width: 150
        }, {
            dataIndex: 'sampleTypeName',
            header: 'Sample Type',
            width: 150
        }, {
            dataIndex: 'sampleTemplateName',
            header: 'Template',
            width: 150
        }, {
            dataIndex: 'attributes',
            header: 'Attributes',
            flex: 1
        }];


        this.callParent();
    }
});