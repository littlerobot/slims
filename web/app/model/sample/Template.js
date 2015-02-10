Ext.define('Slims.model.sample.Template', {
    extend: 'Ext.data.Model',
    idProprerty: 'id',

    fields: [{
        name: 'id'
    }, {
        name: 'name'
    }, {
        name: 'stored',
        type: 'auto',
        useNull: true
    }, {
        name: 'removed',
        type: 'auto',
        useNull: true
    }, {
    	name: 'editable',
        type: 'bool',
        defaultValue: true
    }]
});
