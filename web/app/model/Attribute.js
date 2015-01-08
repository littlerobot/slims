Ext.define('Slims.model.Attribute', {
    extend: 'Ext.data.Model',
    idProprerty: 'order',

    fields: [{
        name: 'order'
    }, {
        name: 'name'
    }, {
        name: 'type'
    }, {
    	name: 'options',
    	type: 'auto'
    }]
});
