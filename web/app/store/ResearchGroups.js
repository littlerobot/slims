Ext.define('Slims.store.ResearchGroups', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.ResearchGroup',

    proxy: {
        type: 'ajax',
        url: Slims.Url.getRoute('getgroups'),
        reader: {
            type: 'json',
            root: 'groups'
        }
    }
});