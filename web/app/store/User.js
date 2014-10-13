/**
 * Store for managing staff
 */
Ext.define('App.store.User', {
    extend: 'App.store.Base',
    alias: 'store.user',
    requires: [
        'App.model.User'
    ],
    restPath: '/api/staff',
    model: 'App.model.User'
});
