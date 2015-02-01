Ext.define('Slims.controller.Main', {
    extend: 'Ext.app.Controller',

    init: function() {
        Ext.Ajax.on('requestexception', this.handleAjaxErrors, this);
    },

    handleAjaxErrors: function(conn, xhr) {
        var response = Ext.decode(xhr.responseText);

        if (response && response.errors) {
            var title = response.errors.message || 'Error appeared!',
                message = 'Server returned an error.';

            if (response.errors.errors.length) {
                message = '';
                Ext.each(response.errors.errors, function(m) {
                    message += m + "</br> ";
                });
            }
        } else {
            var title = 'Internal error',
                message = 'Server returned an error.';
        }

        this.error(title, message);
    },

    error: function(title, message) {
        Ext.Msg.show({
            title: title,
            msg: message,
            width: 300,
            buttons: Ext.Msg.OK,
            icon: Ext.Msg.ERROR
        });
    }
});
