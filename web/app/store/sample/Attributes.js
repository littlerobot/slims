Ext.define('Slims.store.sample.Attributes', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.sample.Attribute',

    proxy: {
        type: 'memory',
        reader: {
            type: 'json',
            root: ''
        }
    }
});