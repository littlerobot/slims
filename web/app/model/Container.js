Ext.define('App.model.Container', {
    extend: 'Ext.data.TreeModel',
    idProperty: 'id',
    requires: [
        'Ext.data.Request'
    ],
    fields: [
        {
            name: 'id',
            type: 'integer'
        },
        {
            name: 'name',
            type: 'string',
            useNull : true
        },
        {
            name: 'owner',
            type: 'string',
            useNull : true
        }
    ]
});
