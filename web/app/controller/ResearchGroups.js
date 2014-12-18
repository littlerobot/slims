Ext.define('Slims.controller.ResearchGroups', {
    extend: 'Ext.app.Controller',

    models: ['ResearchGroup'],
    stores: ['ResearchGroups'],
    views: ['groups.Grid', 'groups.Window'],

    refs: [{
        ref: 'groupsGrid',
        selector: 'groupsgrid'
    }],

    init: function() {
        this.control({
            'groupsgrid button[name=addGroup]': {
                click: this.openAddGroupDialog
            },
            'groupsgrid': {
                deleterecord: this.confirmDeleteAction,
                editrecord: this.openEditGroupDialog
            },
            'groupwindow': {
                save: this.saveGroup
            }
        });

        this.createResearchGroupsStore();
    },

    createResearchGroupsStore: function() {
        var store = Ext.create('Slims.store.ResearchGroups', {
            storeId: 'researchGroups'
        });

        store.load();
    },

    openEditGroupDialog: function(group) {
        var editGroupWindow = Ext.create('Slims.view.groups.Window', {
            record: group
        });

        editGroupWindow.show();
    },

    openAddGroupDialog: function() {
        var addGroupWindow = Ext.create('Slims.view.groups.Window');

        addGroupWindow.show();
    },

    confirmDeleteAction: function(group) {
        var title = 'Delete confirmation',
            msg = Ext.String.format('Are you sure you want to delete group "{0}"?', group.get('name'));

        Ext.Msg.confirm(title, msg, function(btn) {
            if (btn == 'yes') {
                this.deleteGroup(group);
            }
        }, this);
    },

    deleteGroup: function(group) {
        this.getGroupsGrid().setLoading(true);

        Ext.Ajax.request({
            url: Slims.Url.getRoute('deletegroup'),
            method: 'POST',
            params: {
                id: group.getId()
            },
            scope: this,
            success: function() {
                this.getGroupsGrid().getStore().load();
                this.getGroupsGrid().setLoading(false);
            },
            failure: function() {
                this.getGroupsGrid().setLoading(false);
                Ext.Msg.alert('Error', 'Server returned error');
            }
        });
    },

    saveGroup: function(group, dialog) {
        dialog.setLoading(true);

        var url;
        if (group.getId()) {
            url = Ext.String.format(Slims.Url.getRoute('setgroup'), group.getId());
        } else {
            url = Slims.Url.getRoute('creategroup');
        }

        Ext.Ajax.request({
            url: url,
            method: 'POST',
            params: {
                name: group.get('name')
            },
            scope: this,
            success: function() {
                this.getGroupsGrid().getStore().load();
                dialog.setLoading(false);
                dialog.close();
            },
            failure: function() {
                dialog.setLoading(false);
                Ext.Msg.alert('Error', 'Server returned error');
            }
        });
    }
});
