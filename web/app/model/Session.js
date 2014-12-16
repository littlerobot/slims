Ext.define('Slims.model.Session', {
    extend: 'Slims.model.Base',
    requires: [
        'Ext.data.Request',
        'Slims.model.User',
        'Ext.data.association.HasOne',
        'Slims.reader.HasOneReader'
    ],
    idProperty: 'sessionId',
    proxy: Ext.create('Slims.proxy.Rest', {
        url: Slims.router.API.getRoute('session')
    }),
    fields: [
        // id field
        {
            name: 'sessionId',
            type: 'string',
            useNull : true
        }
    ],
    associations: [
        {
            model: 'Slims.model.User',
            type: 'hasOne',
            associationKey: 'User',
            associatedName: 'User',
            reader: {
                'type': 'hasone'
            }
        }
    ]
});
