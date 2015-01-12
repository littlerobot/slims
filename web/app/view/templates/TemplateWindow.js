Ext.define('Slims.view.templates.TemplateWindow', {
    extend: 'Ext.window.Window',
    xtype: 'templatewindow',

    requires: [
        'Ext.form.field.Text',
        'Ext.toolbar.Toolbar',
        'Ext.form.field.Checkbox'
    ],

    width: 400,
    layout: 'fit',
    modal: true,

    name: null,
    title: 'Add new template',

    initComponent: function() {

        this.items = [{
            xtype: 'form',
            defaults: {
                labelStyle: 'margin-bottom: 3px;',
                anchor: '100%',
                margin: 10,
                labelWidth: 50
            },
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Name',
                name: 'name',
                allowBlank: false
            }]
        }];

        this.bbar = ['->', {
            text: 'Save',
            icon: '/resources/images/save.png',
            width: 80,
            name: 'save',
            scope: this,
            handler: function() {
                var user = this.record;

                if (!this.down('form').getForm().isValid())
                    return;

                var name = this.down('textfield[name=name]').getValue();

                var template = Ext.create('Slims.model.Template', {name: name});
                this.fireEvent('save', template, this);
            }
        }, {
            text: 'Cancel',
            icon: '/resources/images/cancel.png',
            width: 80,
            scope: this,
            handler: this.close
        }];

        this.on('afterrender', this.setupData, this);

        this.callParent();
    },

    setupData: function() {
        this.down('form').getForm().setValues(this.name || '');
    }
});