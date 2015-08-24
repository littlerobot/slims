Ext.define('Slims.view.sample.wizard.AttributesForm', {
    extend: 'Ext.form.Panel',
    xtype: 'attributesform',
    layout: 'vbox',

    initComponent: function() {
        this.buildItems();
        this.callParent(arguments);
    },

    buildItems: function() {
        var attributesFieldset = Ext.create('Ext.form.FieldSet', {
            name: 'attributesFieldset',
            title: 'Attributes',
            width: '100%',
            padding: 10,
            margin: 10
        });

        var colorPicker = Ext.create('Slims.ux.ColorButton', {
            name: 'samplesColor',
            labelWidth: 180,
            margin: 20,
            fieldLabel: 'Color for new samples'
        });

        this.items = [attributesFieldset, colorPicker];
    },

    setValues: function(values) {
        if (values.samplesColor) {
            this.setColor(values.samplesColor);
        }

        var formValues = {};
        for (var id in values) {
            formValues['id-'+id] = values[id];
        }
        this.getForm().setValues(formValues);
    },

    setColor: function(color) {
        this.down('[name=samplesColor]').setValue(color);
    },

    getColor: function() {
        var color = this.down('[name=samplesColor]').getValue();
        return color;
    },

    getValues: function() {
        var formValues = this.getForm().getValues(),
            values = {};

        for (var id in formValues) {
            values[id.replace('id-', '')] = formValues[id];
        }
        values.samplesColor = this.down('[name=samplesColor]').getValue();

        return values;
    },

    getAttributesValues: function() {
        var values = this.getValues();
        delete values.samplesColor;

        return values;
    },

    loadAttributes: function(attributes) {
        var fields = [];
        Ext.each(attributes, function(attr) {
            var field = this.createField(attr.data || attr);
            fields.push(field);
        }, this);

        var attributesFieldset = this.down('[name=attributesFieldset]');
        attributesFieldset.removeAll();
        attributesFieldset.items.add(fields);
        attributesFieldset.doLayout();
    },

    createField: function(attribute) {
        var field,
            generalParameters = {
                name: 'id-'+attribute.id,
                padding: 3,
                allowBlank: false,
                anchor: '100%',
                labelWidth: 180,
                fieldLabel: attribute.label
            };

        switch (attribute.type) {
            case 'option':
                field = Ext.create('Ext.form.field.ComboBox', Ext.apply(generalParameters, {
                    editable: false,
                    store: attribute.options,
                    valueField: 'name',
                    displayField: 'name'
                }));
                break;
            case 'long-text':
                field = Ext.create('Ext.form.field.TextArea', Ext.apply(generalParameters, {
                    height: 150
                }));
                break;
            case 'date':
                field = Ext.create('Ext.form.field.Date', generalParameters)
                break;
            case 'colour':
                field = Ext.create('Slims.ux.ColorButton', generalParameters);
                break;
            case 'document':
                field = Ext.create('Slims.ux.FileField', generalParameters);
                break;
            default:
                field = Ext.create('Ext.form.field.Text', generalParameters);
                break;
        }
        return field;
    }
});