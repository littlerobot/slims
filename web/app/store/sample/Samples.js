Ext.define('Slims.store.sample.Samples', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.sample.Sample',

    proxy: {
        type: 'ajax',
        url: Slims.Url.getRoute('getsamples'),
        reader: {
            type: 'json',
            root: 'samples'
        }
    }
});