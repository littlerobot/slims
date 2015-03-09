Ext.define('Slims.view.sample.types.Window', {
    extend: 'Ext.window.Window',
    xtype: 'sampletypewindow',

    requires: [
        'Slims.view.sample.types.Form'
    ],

    title: 'Sample type',
    layout: 'fit',
    width: 500,
    record: null,

    initComponent: function() {
        this.items = [{
            xtype: 'sampletypeform'
        }];

        this.bbar = ['->', {
            text: 'Save',
            name: 'save',
            icon: '/resources/images/save.png',
            width: 100,
            scope: this,
            handler: function() {
                if (!this.down('sampletypeform').getForm().isValid())
                    return;

                var form = this.down('sampletypeform').getForm(),
                    formValues = form.getValues(),
                    attributesFieldset = this.down('[name=attributesFieldset]'),
                    attributes = [];

                Ext.each(attributesFieldset.items.items, function(field) {
                    attributes.push({
                        sample_type_template_attribute: field.name,
                        value: field.getValue()
                    });
                });

                var sampleType = Ext.create('Slims.model.sample.Type', {
                    name: formValues.name,
                    sample_type_template: formValues.templateId,
                    attributes: attributes
                });

                this.fireEvent('save', sampleType, this);
            }
        },{
            text: 'Cancel',
            name: 'cancel',
            icon: '/resources/images/cancel.png',
            width: 100,
            scope: this,
            handler: this.close
        }];

        this.callParent();
    }
});
