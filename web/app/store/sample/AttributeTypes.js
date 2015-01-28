Ext.define('Slims.store.sample.AttributeTypes', {
    extend: 'Ext.data.Store',

    fields: ['id', 'name'],

    proxy: { type: 'memory' },

    data: [{
        name: 'Brief text',
        id: 'brief-text'
    }, {
        name: 'Long text',
        id: 'long-text'
    }, {
        name: 'Document',
        id: 'document'
    }, {
        name: 'Option',
        id: 'option'
    }, {
        name: 'Date',
        id: 'date'
    }, {
        name: 'Colour',
        id: 'colour'
    }, {
        name: 'User',
        id: 'user'
    }]
});