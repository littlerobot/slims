Ext.define('Slims.controller.Main', {
    extend: 'Ext.app.Controller',

    init: function() {
        Ext.tip.QuickTipManager.init();
        Ext.Ajax.on('requestexception', this.handleAjaxErrors, this);

        Ext.form.field.Date.prototype.format = 'd/m/Y';
    },

    handleAjaxErrors: function(conn, xhr) {
        var response = Ext.decode(xhr.responseText);

        if (response && response.errors) {
            var title = response.errors.message || 'Error appeared!',
                message = 'Server returned an error.';

            if (response.errors.children) {
                message = '';
                var errorsList = response.errors.children;
                for (var fname in errorsList) {
                    var errors = errorsList[fname].errors;
                    Ext.each(errors, function(m) {
                        message += m + "</br> ";
                    });
                }
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
