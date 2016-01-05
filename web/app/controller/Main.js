Ext.define('Slims.controller.Main', {
    extend: 'Ext.app.Controller',

    init: function() {
        Ext.tip.QuickTipManager.init();
        Ext.Ajax.on('requestexception', this.handleAjaxErrors, this);
        Ext.Ajax.defaultHeaders = {Accept: 'application/json'};

        Ext.form.field.Date.prototype.format = 'd/m/Y';
    },

    handleAjaxErrors: function(conn, xhr) {
        var response = Ext.decode(xhr.responseText);
        if (response && response.errors) {
            var title = response.errors.message || '',
                message = 'The server returned an error.';

            if (response.errors.children) {
                message = '';
                var errorsList = response.errors.children;
                for (var fname in errorsList) {
                    var errors = errorsList[fname].errors;
                    Ext.each(errors, function(m) {
                        message += Ext.String.format('<li>{0}</li>', m);
                    });
                }
                if (message) {
                    message += '<ul>' + message + '</ul>';
                } else {
                    message = 'The server returned an error.'
                }
            }
        } else {
            var title = 'Server error',
                message = 'The server returned an error.';
        }

        this.showError(title, message);
    },

    showError: function(title, message) {
        Ext.Msg.show({
            title: title,
            msg: message,
            width: 400,
            buttons: Ext.Msg.OK
        });
    }
});
