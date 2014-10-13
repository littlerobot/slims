Ext.define('App.model.CurrentUser', {
    extend: 'App.model.User',
    requires: [
        'App.proxy.Rest'
    ]
});
