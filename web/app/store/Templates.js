Ext.define('Slims.store.Templates', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.Template',

    proxy: {
        type: 'ajax',
        url: Slims.Url.getRoute('gettemplates'),
        reader: {
            type: 'json',
            root: 'sample_type_templates'
        }
    }
});