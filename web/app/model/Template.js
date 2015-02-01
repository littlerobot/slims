Ext.define('Slims.model.Template', {
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
