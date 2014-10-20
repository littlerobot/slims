Ext.define('App.controller.Login', {
    extend: 'Ext.app.Controller',
    init: function() {
        this.application.on('apiresponse401', this.on401);
    },
    on401: function(request) {
        Ext.Msg.show({
            title: 'Session expired',
            msg: 'Your request could not be completed because your session has expired.',
            buttonText: {ok: 'Log in'},
            buttons: Ext.Msg.OK,
            icon: Ext.Msg.WARNING,
            fn: function(btn) {
                if (btn === 'ok') {
                    window.location.reload();
                }
            }
        });
    }
});
