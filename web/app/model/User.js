Ext.define('Slims.model.User', {
    extend: 'Ext.data.Model',
    idProprerty: 'id',

    fields: [{
        name: 'username'
    }, {
        name: 'name'
    }, {
        name: 'research_group',
        type: 'string',
        useNull: true,
        defaultValue: null
    }, {
        name: 'is_active',
        type: 'bool'
    }]
});
