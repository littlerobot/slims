Ext.define('App.controller.ResearchGroups', {
    extend: 'Ext.app.Controller',

    models: ['ResearchGroup'],
    stores: ['ResearchGroups'],
    views: ['groups.Grid', 'groups.Window'],

    refs: [{
        ref: 'groupsGrid',
        selector: 'researchgroups-grid'
    }],

    init: function() {
        this.control({
            'researchgroups-grid button[name=addGroup]': {
                click: this.openAddGroupDialog
            },
            'researchgroups-grid': {
                deleterecord: this.confirmDeleteAction,
                editrecord: this.openEditGroupDialog
            }
        });
    },

    openEditGroupDialog: function(group) {
        var editGroupWindow = Ext.create('App.view.groups.Window', {
            record: group
        });

        editGroupWindow.show();
    },

    openAddGroupDialog: function() {
        var addGroupWindow = Ext.create('App.view.groups.Window');

        addGroupWindow.show();
    },

    confirmDeleteAction: function(group) {
        var title = 'Delete confirmation',
            msg = Ext.String.format('Are you sure you want to delete group "{0}"?', group.get('name'));

        Ext.Msg.confirm(title, msg, function(btn) {
            if (btn == 'ok') {
                this.deleteGroup(group);
            }
        }, this);
    },

    deleteGroup: function(group) {

    }
});
