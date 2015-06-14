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
            title: 'Store attributes fields',
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
        this.getForm().setValues(values);
        if (values.samplesColor) {
            this.setColor(values.samplesColor);
        }
    },

    setColor: function(color) {
        this.down('[name=samplesColor]').setValue(color);
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
                padding: 3,
                allowBlank: false,
                anchor: '100%',
                labelWidth: 180,
                name: attribute.id.toString(),
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
                field = Ext.create('Ext.form.field.File', Ext.apply(generalParameters, {
                    buttonText: 'Select',
                    listeners: {
                        change: this.readFile,
                        scope: this
                    }
                }));
                break;
            default:
                field = Ext.create('Ext.form.field.Text', generalParameters);
                break;
        }
        return field;
    },

    readFile: function(field, val) {
        // replace fake path
        var node = Ext.DomQuery.selectNode('input[id='+field.getInputId()+']');
        node.value = val.replace("C:\\fakepath\\","");

        var filesList = field.el.down('input[type=file]').el.dom.files,
            file = filesList[0];

        var form = this;
        form.setLoading('Read the file...');
        var reader = new FileReader();
        reader.onload = function(e) {
            var file = e.target.result;
            field.theFile = btoa(file);
            form.setLoading(false);
        };
        field.file_name = file.name;
        reader.readAsBinaryString(file);
    }
});