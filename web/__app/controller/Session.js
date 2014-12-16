Ext.define('Slims.controller.Session', {
    extend: 'Ext.app.Controller',
    requires: [
        'Slims.router.API',
        'Slims.session.Session'
    ],
    init: function() {
        Slims.session.Session.start(this.onSessionStart);
    },
    onSessionStart: function() {
        console.log(Slims.session.Session.getCurrentUser());
    }
});
