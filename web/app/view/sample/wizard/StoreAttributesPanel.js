Ext.define('Slims.view.sample.wizard.StoreAttributesPanel', {
    extend: 'Ext.form.Panel',

    layout: 'fit',

    initComponent: function() {
        this.buildItems();
        this.callParent(arguments);
    },

    buildItems: function() {
        var attributesFieldset  = Ext.create('Ext.form.FieldSet', {
            name: 'attributesFieldset',
            title: 'Attributes',
            padding: 10,
            margin: 10
        });

        this.items = [attributesFieldset];
    },

    loadAttributes: function(attributes) {
        var fields = [];
        Ext.each(attributes, function(attr) {
            var field = this.createField(attr.data);
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
                msgTarget: 'under',
                labelWidth: 180,
                name: attribute.id,
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