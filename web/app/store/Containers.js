Ext.define('Slims.store.Containers', {
    extend: 'Ext.data.TreeStore',

    model: 'Slims.model.Container',
    proxy: {
        type: 'ajax',
        url: Slims.Url.getRoute('containers'),
        reader: {
            type: 'json',
            root: 'data',
            totalProperty: 'count',
            implicitIncludes: true
        }
    },
    folderSort: true
});