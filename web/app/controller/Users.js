Ext.define('Slims.controller.Users', {
    extend: 'Ext.app.Controller',

    models: ['User'],
    stores: ['Users'],
    views: ['users.Grid', 'users.Window'],

    refs: [{
        ref: 'usersGrid',
        selector: 'usersgrid'
    }],

    init: function() {
        this.control({
            'usersgrid button[name=addUser]': {
                click: this.openAddUserWindow
            },
            'usersgrid button[name=reloadGrid]': {
                click: this.reloadGrid
            },
            'usersgrid': {
                'editrecord': this.openEditUserWindow
            },
            'userwindow': {
                save: this.saveUser
            }
        });
    },

    openAddUserWindow: function() {
        var addUserWindow = Ext.create('Slims.view.users.Window');

        addUserWindow.show();
    },

    openEditUserWindow: function(user) {
        var editUserWindow = Ext.create('Slims.view.users.Window', {
            record: user
        });

        editUserWindow.show();
    },

    saveUser: function(user, dialog) {
        dialog.setLoading(true);

        var url;
        if (user.getId()) {
            url = Ext.String.format(Slims.Url.getRoute('setuser'), user.getId());
        } else {
            url = Slims.Url.getRoute('createuser');
        }

        var userData = user.getData();
        delete userData.id;

        Ext.Ajax.request({
            url: url,
            method: 'POST',
            params: userData,
            scope: this,
            success: function() {
                this.getUsersGrid().getStore().load();
                dialog.setLoading(false);
                dialog.close();
            },
            failure: function() {
                dialog.setLoading(false);
            }
        });
    },

    reloadGrid: function() {
        this.getUsersGrid().getStore().reload();
    }
});
