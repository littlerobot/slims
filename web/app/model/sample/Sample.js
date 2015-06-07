Ext.define('Slims.model.sample.Sample', {
    extend: 'Ext.data.Model',
    idProprerty: 'id',

    fields: [{
        name: 'id'
    }, {
        name: 'name'
    }, {
        name: 'positionId'
    }, {
        name: 'sampleType'
    }, {
        name: 'sampleInstanceTemplate'
    }, {
        name: 'colour'
    }]
});