Ext.define('App.model.Session', {
    extend: 'App.model.Base',
    requires: [
        'Ext.data.Request',
        'App.model.User',
        'Ext.data.association.HasOne',
        'App.reader.HasOneReader'
    ],
    idProperty: 'sessionId',
    proxy: Ext.create('App.proxy.Rest', {
        url: App.router.API.getRoute('session')
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
            model: 'App.model.User',
            type: 'hasOne',
            associationKey: 'User',
            associatedName: 'User',
            reader: {
                'type': 'hasone'
            }
        }
    ]
});
