Ext.define('Slims.view.sample.types.Form', {
    extend: 'Ext.panel.Panel',
    xtype: 'sampletypeform',

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
            fieldLabel: 'Template',
            editable: false,
            store: Ext.StoreMgr.get('templates'),
            queryMode: 'local',
            displayField: 'name',
            valueField: 'id'
        });

        return templatesCombo;
    },

    getAttributesPanel: function() {
        return {
            xtype: 'panel'
        };
    }
});