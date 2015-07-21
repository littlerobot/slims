Ext.define('Slims.view.sample.search.Grid', {
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

        this.store.on('load', this.parseData, this);
        this.callParent();
    },

    parseData: function() {

    }
});