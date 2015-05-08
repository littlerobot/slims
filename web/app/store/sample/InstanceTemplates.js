Ext.define('Slims.store.sample.InstanceTemplates', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.sample.InstanceTemplate',

    proxy: {
        type: 'ajax',
        url: Slims.Url.getRoute('getsampleinstancetemplates'),
        reader: {
            type: 'json',
            root: 'sample_instance_templates'
        }
    }
});