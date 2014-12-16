Ext.define('Slims.model.Container', {
    extend: 'Ext.data.TreeModel',
    idProperty: 'id',

    fields: [{
        name: 'id',
        type: 'integer'
    }, {
        name: 'rows',
        type: 'integer'
    }, {
        name: 'columns',
        type: 'integer'
    }, {
        name: 'name',
        type: 'string',
        useNull : true
    }, {
        name: 'research_group',
        type: 'string',
        useNull : true
    }, {
        name: 'stores',
        type: 'string',
        useNull : true
    }, {
        name: 'colour',
        type: 'string',
        useNull : true
    }]
});
