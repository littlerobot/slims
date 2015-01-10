Ext.define('Slims.store.Templates', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.Template',

    proxy: {
        type: 'ajax',
        url: '/mock-responses/templates.json',
        // url: Slims.Url.getRoute('gettemplates'),
        reader: {
            type: 'json',
            root: 'templates'
        }
    }
});