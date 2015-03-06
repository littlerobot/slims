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
            this.getMainInfoPanel(),
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

    getMainInfoPanel: function() {
        return {
            xtype: 'fieldset',
            title: 'Main info',
            padding: 10,
            margin: 10,
            items: [{
                xtype: 'combo',
                name: 'templatescombo',
                fieldLabel: 'Sample type template',
                emptyText: 'Select to continue',
                width: 500,
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
                width: 500,
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
                labelWidth: 180,
                padding: 3
            };
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
