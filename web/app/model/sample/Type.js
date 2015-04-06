Ext.define('Slims.model.sample.Type', {
    extend: 'Ext.data.Model',
    idProprerty: 'id',

    fields: [{
        name: 'id'
    }, {
        name: 'name'
    }, {
        name: 'sample_type_template'
    }, {
        name: 'attributes',
        type: 'auto'
    }]
});
