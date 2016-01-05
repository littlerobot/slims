Ext.define('Slims.ux.Utils', {
    extend: 'Ext.util.Observable',
    alternateClassName: 'Utils',
    singleton: true,

    getFieldByType: function(type, config) {
        var field;

        switch (type) {
            case 'option':
                field = Ext.create('Ext.form.field.ComboBox', Ext.apply(config, {
                    editable: false,
                    store: attribute.options,
                    valueField: 'name',
                    displayField: 'name'
                }));
                break;
            case 'long-text':
                field = Ext.create('Ext.form.field.TextArea', Ext.apply(config, {
                    height: 150
                }));
                break;
            case 'date':
                field = Ext.create('Ext.form.field.Date', config);
                break;
            case 'colour':
                field = Ext.create('Slims.ux.ColorButton', config);
                break;
            case 'document':
                field = Ext.create('Slims.ux.FileField', config);
                break;
            default:
                field = Ext.create('Ext.form.field.Text', config);
                break;
        }

        return field;
    }
});
