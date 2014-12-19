Ext.define('Slims.model.User', {
    extend: 'Ext.data.Model',
    idProprerty: 'id',

    fields: [{
        name: 'username'
    }, {
        name: 'name'
    }, {
        name: 'research_group'
    }, {
        name: 'is_active'
    }]
});
