Ext.define('Slims.model.Attribute', {
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
    }]
});
