Ext.define('Slims.view.sample.types.Form', {
    extend: 'Ext.panel.Panel',
    xtype: 'sampletypeform',

    requires: [
        'Ext.form.FieldSet',
        'Ext.form.field.Text',
        'Ext.form.field.TextArea',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Date'
    ],

    initComponent: function() {
        this.items = [
            this.getTemplateCombo(),
            this.getAttributesPanel()
        ];

        this.bbar = ['->', {
            text: 'Save',
            name: 'save',
            width: 120
        },{
            text: 'Cancel',
            name: 'cancel',
            width: 120
        }];

        this.callParent();
    },

    getTemplateCombo: function() {
        var templatesCombo = Ext.create('Ext.form.field.ComboBox', {
            name: 'templatescombo',
            style: 'margin-left: 5px;',
            flex: 1,
            emptyText: 'Sample type template',
            padding: 10,
            width: 300,
            editable: false,
            hideLabel: true,
            store: Ext.StoreMgr.get('templates'),
            queryMode: 'local',
            displayField: 'name',
            valueField: 'id'
        });

        templatesCombo.on('change', function(combo, value) {
            var template = combo.store.findRecord(combo.valueField, value),
                attributes = template.get('attributes');
                this.loadAttributes(attributes);
        }, this);

        return templatesCombo;
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

        var disableButton = !fields.length;
        this.down('[name=save]').setDisabled(disableButton);
        this.down('[name=cancel]').setDisabled(disableButton);
    },

    createField: function(attributes) {
        var field,
            defaults = {
                width: 500,
                labelWidth: 200,
                padding: 3
            }
        switch (attributes.type) {
            case 'option':
                field = Ext.create('Ext.form.field.ComboBox', Ext.apply(defaults, {
                    store: attributes.options,
                    fieldLabel: attributes.label,
                    valueField: 'name',
                    displayField: 'name'
                }));
                break;
            case 'long-text':
                field = Ext.create('Ext.form.field.TextArea', Ext.apply(defaults, {
                    name: attributes.id,
                    fieldLabel: attributes.label,
                    height: 150
                }));
                break;
            case 'date':
                field = Ext.create('Ext.form.field.Date', Ext.apply(defaults, {
                    name: attributes.id,
                    fieldLabel: attributes.label
                }))
                break;
            default:
                field = Ext.create('Ext.form.field.Text', Ext.apply(defaults, {
                    name: attributes.id,
                    fieldLabel: attributes.label
                }));
                break;
        }
        return field;
    }
});
