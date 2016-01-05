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
    },

    listeners: {
        load: function (store) {
            var model = Ext.create('Slims.model.ResearchGroup', {
                id: null,
                name: 'None'
            });

            store.autoSync = false;
            store.insert(0, model);
        }
    }
});
