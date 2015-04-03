Ext.define('Slims.view.sample.types.Form', {
    extend: 'Ext.form.Panel',
    xtype: 'sampletypeform',

    requires: [
        'Ext.form.FieldSet',
        'Ext.form.field.Text',
        'Ext.form.field.TextArea',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Date',
        'Ext.form.field.File',
        'Slims.ux.ColorButton'
    ],

    templateId: null, // default sample type template value

    initComponent: function() {
        this.items = [
            this.getMainInfoPanel(),
            this.getAttributesPanel()
        ];

        this.on('afterrender', this.setDefaultTemplate, this);

        this.callParent();
    },

    getMainInfoPanel: function() {
        return {
            xtype: 'fieldset',
            title: 'Main info',
            padding: 10,
            margin: 10,
            items: [{
                xtype: 'combo',
                name: 'templateId',
                fieldLabel: 'Sample type template',
                emptyText: 'Select to continue',
                anchor: '100%',
                labelWidth: 180,
                allowBlank: false,
                editable: false,
                store: Ext.StoreMgr.get('templates'),
                queryMode: 'local',
                displayField: 'name',
                valueField: 'id',
                listeners: {
                    change: function(combo, value) {
                        var template = combo.store.findRecord(combo.valueField, value),
                            attributes = template.get('attributes');
                        this.loadAttributes(attributes);
                    },
                    scope: this
                }
            }, {
                xtype: 'textfield',
                name: 'name',
                allowBlank: false,
                fieldLabel: 'Name',
                anchor: '100%',
                labelWidth: 180
            }]
        };
    },

    getAttributesPanel: function() {
        var attributesFieldset  = Ext.create('Ext.form.FieldSet', {
            name: 'attributesFieldset',
            title: 'Attributes',
            padding: 10,
            margin: 10
        });
        return attributesFieldset;
    },

    setData: function(data) {
        this.down('[name=name]').setValue(data.name);

        attributesFieldset = this.down('[name=attributesFieldset]');
        Ext.each(data.attributes, function(attribute) {
            var fieldName = attribute.sample_type_template,
                field = attributesFieldset.down('[name='+fieldName+']');

            if (field) {
                if (field.xtype == 'datefield') {
                    field.setValue(new Date(attribute.value));
                } else {
                    field.setValue(attribute.value);
                }
            }
        }, this);
    },

    loadAttributes: function(attributes) {
        var fields = [];
        Ext.each(attributes, function(attr) {
            var field = this.createField(attr);
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
                anchor: '100%',
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
                    buttonText: 'Select document',
                    buttonOnly: true
                }));
                break;
            default:
                field = Ext.create('Ext.form.field.Text', generalParameters);
                break;
        }
        return field;
    },

    setDefaultTemplate: function() {
        if (this.templateId) {
            this.down('combo[name=templateId]').setValue(this.templateId);
        }
    }
});
