Ext.define('App.store.ResearchGroups', {
    extend: 'Ext.data.Store',

    model: 'App.model.ResearchGroup',

    // proxy: {
    //     type: 'json',
    //     url: 'getresearchgroupslist'
    // },
    proxy: {
        type: 'memory'
    },
    data: [{
        id: 1,
        name: 'Group name #1'
    }, {
        id: 2,
        name: 'Group name #2'
    }]
});