Ext.define('Slims.model.sample.Attribute', {
    extend: 'Ext.data.Model',
    idProprerty: 'id',

    fields: [{
        name: 'order',
        type: 'int'
    }, {
        name: 'label'
    }, {
        name: 'type'
    }, {
    	name: 'options',
    	type: 'auto',
        useNull: true
    }, {
        name: 'display',
        type: 'int'
    }]
});
