Ext.define('App.model.User', {
    extend: 'App.model.Base',
    idProperty: 'crsid',
    requires: [
        'Ext.data.Request'
    ],
    fields: [
        // id field
        {
            name: 'crsid',
            type: 'string',
            useNull : true
        }
    ]
});
