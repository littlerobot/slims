Ext.define('Slims.model.sample.Template', {
    extend: 'Ext.data.Model',
    idProprerty: 'id',

    fields: [{
        name: 'id'
    }, {
        name: 'name'
    }, {
        name: 'attributes'
    }, {
    	name: 'editable',
        type: 'bool',
        defaultValue: true
    }]
});
