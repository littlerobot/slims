Ext.define('App.router.API', {
    singleton: true,
    getRoute: function(routeName, params) {
        var routePrefix = 'api';
        switch (routeName) {
            case 'containers':
                return routePrefix + '/containers';
            case 'session':
                return routePrefix + '/session';
            default:
                throw 'Unknown route: ' + routeName;
        }
    }
});
