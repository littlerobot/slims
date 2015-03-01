Ext.define('Slims.store.sample.Templates', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.sample.Template',

    proxy: {
        type: 'ajax',
        url: Slims.Url.getRoute('getsampletemplates'),
        reader: {
            type: 'json',
            root: 'sample_instance_templates'
        }
    }
});