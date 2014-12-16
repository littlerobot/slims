Ext.define('Slims.session.Session', {
    singleton: true,
    extend: 'Ext.Base',
    requires: [
        'Slims.reader.MyReader',
        'Slims.reader.HasOneReader',
        'Slims.model.Session'
    ],
    mixins: {
        observable: 'Ext.util.Observable'
    },
    config: {
        sessionModel: null,
        sessionStarted: false
    },
    constructor: function(config) {
        var me = this;
        me.initConfig(config);
        me.mixins.observable.constructor.call(this, config);
        me.addEvents(['sessionStarted']);
    },
    start: function(callback) {
        var me = this;
        if (!this.getSessionStarted()) {
            Slims.model.Session.load(null, {
                success: function(user) {
                    me.setSessionModel(user);
                    me.setSessionStarted(true);
                    me.fireEvent('sessionStarted');
                    if (callback) {
                        Ext.callback(callback);
                    }
                },
                failure: function(record, operation) {
                    console.log(operation.error);
                }
            });
        }
    },
    getCurrentUser: function() {
        var session = this.getSessionModel();
        if (Ext.isFunction(session.getUser)) {
            console.log("TODO: This triggers 'undefined is not a function' in the ModelManager");
            //return session.getUser();
            return null;
        }
        return null;
    },
    isLoggedIn: function() {
        return this.getCurrentUser() !== null;
    }
});
