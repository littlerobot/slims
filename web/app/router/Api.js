Ext.define('Slims.router.Api', {
    alternateClassName: 'Slims.Url',
    singleton: true,

    getRoute: function(routeName, params) {
        if (window.location.hostname == 'localhost') {
            return this.getTestRoute(routeName, params);
        }

        var routePrefix = 'api';

        switch (routeName) {
            // templates
            case 'createtemplate':
                return routePrefix + '/sample-type-templates';
            case 'settemplate':
                return routePrefix + '/sample-type-templates/{0}';
            case 'gettemplates':
                return routePrefix + '/sample-type-templates';
            // containers
            case 'getcontainers':
                return routePrefix + '/containers';
            case 'setcontainer':
                return routePrefix + '/containers/{0}';
            case 'createcontainer':
                return routePrefix + '/containers';
            // groups
            case 'getgroups':
                return routePrefix + '/research-groups';
            case 'setgroup':
                return routePrefix + '/research-groups/{0}';
            case 'creategroup':
                return routePrefix + '/research-groups';
            // users
            case 'getusers':
                return routePrefix + '/users';
            case 'setuser':
                return routePrefix + '/users/{0}';
            case 'createuser':
                return routePrefix + '/users';
            // sample templates
            case 'createsampletemplate':
                return routePrefix + '/sample-instance-templates';
            case 'setsampletemplate':
                return routePrefix + '/sample-instance-templates/{0}';
            case 'getsampletemplates':
                return routePrefix + '/sample-instance-templates';
            // sample types
            case 'getsampletypes':
                return routePrefix + '/sample-types';

            default:
                return routePrefix + '/' + routeName;
        }
    },

    getTestRoute: function(routeName, params) {
        var routePrefix = '/api',
            devController = '/app_dev.php';

        switch (routeName) {
            // templates
            case 'createtemplate':
                return devController + routePrefix + '/sample-type-templates';
            case 'settemplate':
                return devController + routePrefix + '/sample-type-templates/{0}';
            case 'gettemplates':
                return devController + routePrefix + '/sample-type-templates';
            // containers
            case 'getcontainers':
                return devController + routePrefix + '/containers';
            case 'setcontainer':
                return devController + routePrefix + '/containers/{0}';
            case 'createcontainer':
                return devController + routePrefix + '/containers';
            // groups
            case 'getgroups':
                return devController + routePrefix + '/research-groups';
            case 'setgroup':
                return devController + routePrefix + '/research-groups/{0}';
            case 'creategroup':
                return devController + routePrefix + '/research-groups';
            // users
            case 'getusers':
                return devController + routePrefix + '/users';
            case 'setuser':
                return devController + routePrefix + '/users/{0}';
            case 'createuser':
                return devController + routePrefix + '/users';
            // sample templates
            case 'createsampletemplate':
                return devController + routePrefix + '/sample-instance-templates';
            case 'setsampletemplate':
                return devController + routePrefix + '/sample-instance-templates/{0}';
            case 'getsampletemplates':
                return devController + routePrefix + '/sample-instance-templates';
            // sample types
            case 'getsampletypes':
                return devController + routePrefix + '/sample-types';

            default:
                return devController + routePrefix + '/' + routeName;
        }
    }
});
