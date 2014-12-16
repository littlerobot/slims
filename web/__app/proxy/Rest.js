Ext.define('Slims.proxy.Rest', {
    extend: 'Ext.data.proxy.Rest',
    alias: 'proxy.baserest',
    format: 'json',
    limitParam: 'max',
    startParam: 'offset',
    sortParam: 'sortorder',
    writer: {
        type: 'json',
        writeAllFields: true
    },
    reader: {
        type: 'json',
        root: 'data',
        totalProperty: 'count',
        implicitIncludes: true
    },
    beforeRequest: function( request, success ) {
        var me = this;
        // fire requestcomplete event
        //me.fireEvent( 'requestcomplete', request, success );
    },
    afterRequest: function( request, success ) {
        if (false === success) {
            if (request.operation &&
                request.operation.error &&
                request.operation.error.status === 401
            ) {
                Slims.getApplication().fireEvent('apiresponse401', request);
            }
        }
    }
});
