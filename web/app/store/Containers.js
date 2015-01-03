Ext.define('Slims.store.Containers', {
    extend: 'Ext.data.TreeStore',

    model: 'Slims.model.Container',
    proxy: {
        type: 'ajax',
        url: Slims.Url.getRoute('getcontainers'),
        reader: {
            type: 'json',
            root: 'data',
            totalProperty: 'count',
            implicitIncludes: true
        }
    },
    folderSort: true
});