Ext.define('App.auth.Auth', {
    singleton: true,
    extend: 'Ext.Component',
    requires: [
        'App.model.CurrentUser'
    ],
    config: {
        currentUser: null
    },
    constructor: function(config) {
        this.initConfig(config);
        App.model.Session.load(null, {
            success: function(user) {
                console.log(user.getId()); //logs 123
            },
            failure: function(record, operation) {
                console.log(operation.error);
            }
        });
    }
});
