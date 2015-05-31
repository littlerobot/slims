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
                return Ext.String.format('{0}/sample-type-templates', routePrefix);
            case 'settemplate':
                return Ext.String.format('{0}/sample-type-templates/{1}', routePrefix, params[0]);
            case 'gettemplates':
                return Ext.String.format('{0}/sample-type-templates', routePrefix);
            // containers
            case 'getcontainers':
                return Ext.String.format('{0}/containers', routePrefix);
            case 'setcontainer':
                return Ext.String.format('{0}/containers/{1}', routePrefix, params[0]);
            case 'createcontainer':
                return Ext.String.format('{0}/containers', routePrefix);
            // groups
            case 'getgroups':
                return Ext.String.format('{0}/research-groups', routePrefix);
            case 'setgroup':
                return Ext.String.format('{0}/research-groups/{1}', routePrefix, params[0]);
            case 'creategroup':
                return Ext.String.format('{0}/research-groups', routePrefix);
            // users
            case 'getusers':
                return Ext.String.format('{0}/users', routePrefix);
            case 'setuser':
                return Ext.String.format('{0}/users/{1}', routePrefix, params[0]);
            case 'createuser':
                return Ext.String.format('{0}/users', routePrefix);
            // sample templates
            case 'createsampleinstancetemplate':
                return Ext.String.format('{0}/sample-instance-templates', routePrefix);
            case 'setsampleinstancetemplate':
                return Ext.String.format('{0}/sample-instance-templates/{1}', routePrefix, params[0]);
            case 'getsampleinstancetemplates':
                return Ext.String.format('{0}/sample-instance-templates', routePrefix);
            // sample types
            case 'getsampletypes':
                return Ext.String.format('{0}/sample-types', routePrefix);
            case 'setsampletype':
                return Ext.String.format('{0}/sample-types/{1}', routePrefix, params[0]);
            case 'createsampletype':
                return Ext.String.format('{0}/sample-types', routePrefix);
            case 'getcontainerpositions':
                return Ext.String.format('{0}/containers/{1}/samples', routePrefix, params[0]);
            // samples
            case 'getsamples':
                return Ext.String.format('{0}/samples', routePrefix);
            case 'setsamples':
                return Ext.String.format('{0}/samples', routePrefix);

            default:
                return routePrefix + '/' + routeName;
        }
    },

    getTestRoute: function(routeName, params) {
        var routePrefix = 'api',
            devController = '/app_dev.php';

        switch (routeName) {
            // templates
            case 'createtemplate':
                return Ext.String.format('{0}/{1}/sample-type-templates', devController, routePrefix);
            case 'settemplate':
                return Ext.String.format('{0}/{1}/sample-type-templates/{2}', devController, routePrefix, params[0]);
            case 'gettemplates':
                return Ext.String.format('{0}/{1}/sample-type-templates', devController, routePrefix);
            // containers
            case 'getcontainers':
                return Ext.String.format('{0}/{1}/containers', devController, routePrefix);
            case 'setcontainer':
                return Ext.String.format('{0}/{1}/containers/{2}', devController, routePrefix, params[0]);
            case 'createcontainer':
                return Ext.String.format('{0}/{1}/containers', devController, routePrefix);
            // groups
            case 'getgroups':
                return Ext.String.format('{0}/{1}/research-groups', devController, routePrefix);
            case 'setgroup':
                return Ext.String.format('{0}/{1}/research-groups/{2}', devController, routePrefix, params[0]);
            case 'creategroup':
                return Ext.String.format('{0}/{1}/research-groups', devController, routePrefix);
            // users
            case 'getusers':
                return Ext.String.format('{0}/{1}/users', devController, routePrefix);
            case 'setuser':
                return Ext.String.format('{0}/{1}/users/{2}', devController, routePrefix, params[0]);
            case 'createuser':
                return Ext.String.format('{0}/{1}/users', devController, routePrefix);
            // sample templates
            case 'createsampleinstancetemplate':
                return Ext.String.format('{0}/{1}/sample-instance-templates', devController, routePrefix);
            case 'setsampleinstancetemplate':
                return Ext.String.format('{0}/{1}/sample-instance-templates/{2}', devController, routePrefix, params[0]);
            case 'getsampleinstancetemplates':
                return Ext.String.format('{0}/{1}/sample-instance-templates', devController, routePrefix);
            // sample types
            case 'getsampletypes':
                return Ext.String.format('{0}/{1}/sample-types', devController, routePrefix);
            case 'setsampletype':
                return Ext.String.format('{0}/{1}/sample-types/{2}', devController, routePrefix, params[0]);
            case 'createsampletype':
                return Ext.String.format('{0}/{1}/sample-types', devController, routePrefix);
            case 'getcontainerpositions':
                return Ext.String.format('{0}/{1}/containers/{2}/samples', devController, routePrefix, params[0]);
            // samples
            case 'getsamples':
                return Ext.String.format('{0}/{1}/samples', devController, routePrefix);
            case 'setsamples':
                return Ext.String.format('{0}/{1}/samples', devController, routePrefix);

            default:
                return devController + routePrefix + '/' + routeName;
        }
    }
});
