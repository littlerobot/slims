Ext.define('Slims.model.User', {
    extend: 'Ext.data.Model',
    idProprerty: 'username',

    fields: [{
        name: 'username'
    }, {
        name: 'name'
    }, {
    	name: 'research_group'
    }]
});
