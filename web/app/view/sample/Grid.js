Ext.define('Slims.view.sample.Grid', {
    extend: 'Ext.grid.Panel',
    xtype: 'samplesgrid',

    layout: 'card',
    border: false,

    initComponent: function() {
        this.store = Ext.create('Slims.store.sample.Samples');
        this.columns = [{
            text: 'container',
            dataIndex: 'container'
        }];

        this.callParent();
        this.on('afterrender', this.loadData, this);
    },

    loadData: function() {
        this.getStore().load();
    }
});
