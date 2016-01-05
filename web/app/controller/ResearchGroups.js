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
                // Waiting for client's answer
                // deleterecord: this.confirmDeleteAction,
                editrecord: this.openEditGroupDialog
            },
            'groupsgrid button[name=reloadGrid]': {
                click: this.reloadGrid
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

    saveGroup: function(group, dialog) {
        dialog.setLoading('Saving. Please wait.');

        var url;
        if (group.getId()) {
            url = Slims.Url.getRoute('setgroup', [group.getId()]);
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
            }
        });
    },

    reloadGrid: function() {
        this.getGroupsGrid().getStore().reload();
    }
});
