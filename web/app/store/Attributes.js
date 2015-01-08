Ext.define('Slims.store.Attributes', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.Attribute',

    proxy: {
        type: 'memory',
        reader: {
            type: 'json',
            root: ''
        }
    }
});