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

        var attributesFieldset = this.down('[name=attributesFieldset]');
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
        Ext.each(attributes, function(attribute) {
            var generalParameters = {
                    padding: 3,
                    allowBlank: false,
                    anchor: '100%',
                    msgTarget: 'under',
                    labelWidth: 180,
                    name: attribute.id,
                    store: attribute.options,
                    fieldLabel: attribute.label
                },
                field = Utils.getFieldByType(attribute.type, generalParameters);

            fields.push(field);
        }, this);

        var attributesFieldset = this.down('[name=attributesFieldset]');
        attributesFieldset.removeAll();
        attributesFieldset.items.add(fields);
        attributesFieldset.doLayout();
    },

    setDefaultTemplate: function() {
        if (this.templateId) {
            this.down('combo[name=templateId]').setValue(this.templateId);
        }
    }
});
