Ext.define('App.controller.Session', {
    extend: 'Ext.app.Controller',
    requires: [
        'App.router.API',
        'App.session.Session'
    ],
    init: function() {
        App.session.Session.start(this.onSessionStart);
    },
    onSessionStart: function() {
        console.log(App.session.Session.getCurrentUser());
    }
});
