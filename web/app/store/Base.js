Ext.define('Slims.store.Base', {
    extend: 'Ext.data.Store',
    requires: [
        'Slims.proxy.Rest'
    ],
    /**
     * @cfg {String} restPath End point for store requests
     */
    restPath: null,
    constructor: function( cfg ){
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'Base',
            remoteSort: true,
            remoteFilter: true,
            remoteGroup: true,
            proxy: {
                type: 'baserest',
                url: me.restPath
            }
        }, cfg)]);
    }
})