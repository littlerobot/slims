Ext.define('Slims.store.sample.Templates', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.sample.Template',

    proxy: {
        type: 'ajax',
        url: Slims.Url.getRoute('getsampletemplates'),
        reader: {
            type: 'json',
            // TODO: fix when api ready
            root: 'sample_type_templates'
        }
    }
});