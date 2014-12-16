Ext.define('Slims.model.User', {
    extend: 'Slims.model.Base',
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
