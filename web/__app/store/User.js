/**
 * Store for managing staff
 */
Ext.define('Slims.store.User', {
    extend: 'Slims.store.Base',
    alias: 'store.user',
    requires: [
        'Slims.model.User'
    ],
    restPath: '/api/staff',
    model: 'Slims.model.User'
});
