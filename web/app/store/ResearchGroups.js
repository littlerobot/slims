Ext.define('App.store.ResearchGroups', {
    extend: 'Ext.data.Store',

    model: 'App.model.ResearchGroup',

    proxy: {
        type: 'ajax',
        url: App.Url.getRoute('getgroups'),
        reader: {
            type: 'json',
            root: 'data'
        }
    }
});