Ext.define('Slims.store.sample.Types', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.sample.Type',

    proxy: {
        type: 'ajax',
        url: Slims.Url.getRoute('getsampletypes'),
        reader: {
            type: 'json',
            root: 'sample_type'
        }
    }
});