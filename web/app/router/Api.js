Ext.define('Slims.router.Api', {
    alternateClassName: 'Slims.Url',
    singleton: true,

    getRoute: function(routeName, params) {
        if (window.location.hostname == 'localhost') {
            return this.getTestRoute(routeName, params);
        }

        var routePrefix = 'api';

        switch (routeName) {
            case 'createtemplate':
                return routePrefix + '/sample-type-templates';
            case 'settemplate':
                return routePrefix + '/sample-type-templates/{0}';
            case 'gettemplates':
                return routePrefix + '/sample-type-templates';
            case 'getcontainers':
                return routePrefix + '/containers';
            case 'setcontainer':
                return routePrefix + '/containers/{0}';
            case 'createcontainer':
                return routePrefix + '/containers';
            case 'session':
                return routePrefix + '/session';
            case 'getgroups':
                return routePrefix + '/research-groups';
            case 'setgroup':
                return routePrefix + '/research-groups/{0}';
            case 'creategroup':
                return routePrefix + '/research-groups';
            case 'getusers':
                return routePrefix + '/users';
            case 'setuser':
                return routePrefix + '/users/{0}';
            case 'createuser':
                return routePrefix + '/users';
            default:
                return routePrefix + '/' + routeName;
        }
    },

    getTestRoute: function(routeName, params) {
        var routePrefix = '/api',
            devController = '/app_dev.php';

        switch (routeName) {
            case 'createtemplate':
                return devController + routePrefix + '/sample-type-templates';
            case 'settemplate':
                return devController + routePrefix + '/sample-type-templates/{0}';
            case 'gettemplates':
                return devController + routePrefix + '/sample-type-templates';
            case 'getcontainers':
                return devController + routePrefix + '/containers';
            case 'setcontainer':
                return devController + routePrefix + '/containers/{0}';
            case 'createcontainer':
                return devController + routePrefix + '/containers';
            case 'session':
                return devController + routePrefix + '/session';
            case 'getgroups':
                return devController + routePrefix + '/research-groups';
            case 'setgroup':
                return devController + routePrefix + '/research-groups/{0}';
            case 'creategroup':
                return devController + routePrefix + '/research-groups';
            case 'getusers':
                return devController + routePrefix + '/users';
            case 'setuser':
                return devController + routePrefix + '/users/{0}';
            case 'createuser':
                return devController + routePrefix + '/users';
            default:
                return devController + routePrefix + '/' + routeName;
        }
    }
});
