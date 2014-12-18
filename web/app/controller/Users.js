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
            }
        });
    },

    openAddUserWindow: function() {
        var addUserWindow = Ext.create('Slims.view.users.Window');

        addUserWindow.show();
    }
});
