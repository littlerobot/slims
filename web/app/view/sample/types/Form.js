Ext.define('Slims.view.sample.types.Form', {
    extend: 'Ext.panel.Panel',
    xtype: 'sampletypeform',

    requires: [
        'Ext.form.FieldSet'
    ],

    initComponent: function() {
        this.items = [
            this.getTemplateCombo(),
            this.getAttributesPanel()
        ];

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
            padding: 10
        });

        return attributesFieldset;
    },

    loadAttributes: function(attributes) {
        var fields = [];
        Ext.each(attributes, function(attr) {
            var xtype = this.FIELD_TYPES[attr.type];
            var field = this.createField(xtype, attr);
            fields.push(field);
        }, this)
    },

    createField: function(xtype, opts) {
        if (xtype == 'xtype.combo') {
            Ext.create(xtype, {
                name: opts.id,
                store: {
                    type: 'array',
                    data: opts.options
                },
                fieldLabel: opts.label
            })
        }
    }
});