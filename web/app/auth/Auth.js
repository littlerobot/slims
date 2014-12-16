Ext.define('Slims.auth.Auth', {
    singleton: true,
    extend: 'Ext.Component',
    requires: [
        'Slims.model.CurrentUser'
    ],
    config: {
        currentUser: null
    },
    constructor: function(config) {
        this.initConfig(config);
        Slims.model.Session.load(null, {
            success: function(user) {
                console.log(user.getId()); //logs 123
            },
            failure: function(record, operation) {
                console.log(operation.error);
            }
        });
    }
});
