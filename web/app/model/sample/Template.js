Ext.define('Slims.model.sample.Template', {
    extend: 'Ext.data.Model',
    idProprerty: 'id',

    fields: [{
        name: 'id'
    }, {
        name: 'name'
    }, {
        name: 'store',
        type: 'auto',
        useNull: true
    }, {
        name: 'remove',
        type: 'auto',
        useNull: true
    }, {
        name: 'editable',
        type: 'bool',
        defaultValue: true
    }]
});
