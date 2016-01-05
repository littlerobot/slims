Ext.define('Slims.store.Users', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.User',

    proxy: {
        type: 'ajax',
        url: Slims.Url.getRoute('getusers'),
        reader: {
            type: 'json',
            root: 'users'
        }
    }
});
