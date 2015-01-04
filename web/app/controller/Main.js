Ext.define('Slims.controller.Main', {
    extend: 'Ext.app.Controller',

    init: function() {
    	Ext.Ajax.on('requestexception', this.handleAjaxErrors, this);
    },

    handleAjaxErrors: function(conn, xhr) {
    	var response = Ext.decode(xhr.responseText);

    	var title = response.errors.message || 'Internal error',
    		message = 'Server returned an error.';

    	if (response.errors.errors.length) {
    		message = '';
    		Ext.each(response.errors.errors, function(m) {
    			message += m + "</br> ";
    		});
    	}

    	Ext.Msg.alert(title, message);
    }
});
