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

    saveUser: function() {

    }
});
