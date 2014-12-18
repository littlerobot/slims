Ext.define('Slims.router.Api', {
    alternateClassName: 'Slims.Url',
    singleton: true,

    getRoute: function(routeName, params) {
        if (window.location.hostname == 'localhost') {
            return this.getTestRoute(routeName, params);
        }

        var routePrefix = 'api';

        switch (routeName) {
            case 'containers':
                return routePrefix + '/containers';
            case 'session':
                return routePrefix + '/session';
            case 'getgroups':
                return routePrefix + '/research-groups';
            case 'setgroup':
                return routePrefix + '/research-groups/{0}';
            case 'creategroup':
                return routePrefix + '/research-groups';
            case 'deletegroup':
                return routePrefix + '/delete-research-group';
            default:
                throw 'Unknown route: ' + routeName;
        }
    },

    getTestRoute: function(routeName, params) {
        var routePrefix = 'api/',
            devController = 'app_dev.php/';

        switch (routeName) {
            case 'containers':
                return devController + routePrefix + 'containers';
            case 'session':
                return devController + routePrefix + 'session';
            case 'getgroups':
                return devController + routePrefix + 'research-groups';
            case 'setgroup':
                return devController + routePrefix + 'research-groups/{0}';
            case 'creategroup':
                return devController + routePrefix + 'research-groups';
            case 'deletegroup':
                return devController + routePrefix + 'delete-research-groups';
        }
    }
});