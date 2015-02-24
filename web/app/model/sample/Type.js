Ext.define('Slims.model.sample.Type', {
    extend: 'Ext.data.Model',
    idProprerty: 'id',

    fields: [{
        name: 'id'
    }, {
        name: 'name'
    }, {
        name: 'attributes',
        type: 'auto'
    }]
});
