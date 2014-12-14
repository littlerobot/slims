Ext.define('App.router.API', {
    alternateClassName: 'App.Url',
    singleton: true,

    getRoute: function(routeName, params) {
        var routePrefix = 'api';
        switch (routeName) {
            case 'containers':
                return routePrefix + '/containers';
            case 'session':
                return routePrefix + '/session';
            case 'getgroups':
                return routePrefix + '/research-group';
            case 'setgroup':
                return routePrefix + '/research-group/{0}';
            case 'creategroup':
                return routePrefix + '/research-group';
            case 'deletegroup':
                return routePrefix + '/';
            default:
                throw 'Unknown route: ' + routeName;
        }
    }
});
