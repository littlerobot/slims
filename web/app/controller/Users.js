Ext.define('Slims.controller.Users', {
    extend: 'Ext.app.Controller',

    models: ['ResearchGroup'],
    stores: ['ResearchGroups'],
    views: ['users.Grid'],

    refs: [{
        ref: 'groupsGrid',
        selector: 'researchgroups-grid'
    }],

    init: function() {
        this.control({

        });
    }
});
