Ext.define('Slims.controller.Templates', {
    extend: 'Ext.app.Controller',

    views: [
        'templates.Panel',
        'templates.TemplateWindow',
        'templates.AttributeWindow'
    ],

    stores: ['Templates', 'Attributes'],
    models: ['Template', 'Attribute'],

    refs: [{
        ref: 'templatesGrid',
        selector: 'templatesgrid'
    }, {
        ref: 'attributesGrid',
        selector: 'attributesgrid'
    }, {
        ref: 'addAttributeButton',
        selector: 'attributesgrid button[name=addAttribute]'
    }],

    init: function() {
        this.control({
            'templatesgrid': {
                select: this.onTemplateSelect
            },
            'templatesgrid button[name=addTemplate]': {
                click: this.openAddTemplateWindow
            },
            'attributesgrid button[name=addAttribute]': {
                click: this.openAddAttributeWindow
            },
            'attributesgrid': {
                editrecord: this.editAttribute,
                attributeschanged: this.commitAttributes
            },
            'templatewindow': {
                save: this.onSaveTemplateClick
            },
            'attributewindow': {
                save: this.saveAttribute
            }
        });
    },

    onTemplateSelect: function(selModel, template) {
        this.loadTemplateAttributes(template);
    },

    loadTemplateAttributes: function(template) {
        this.getAddAttributeButton().setDisabled(false);
        this.getAttributesGrid().getStore().loadData(template.get('attributes'));
    },

    openAddTemplateWindow: function() {
        var window = Ext.create('Slims.view.templates.TemplateWindow');

        window.show();
    },

    openAddAttributeWindow: function() {
        var window = Ext.create('Slims.view.templates.AttributeWindow');

        window.show();
    },

    editAttribute: function(attribute) {
        var window = Ext.create('Slims.view.templates.AttributeWindow', {
            attribute: attribute.getData()
        });

        window.show();
    },

    commitAttributes: function(attributes) {
        this.getAttributesGrid().setLoading(true);
        this.getTemplatesGrid().setLoading(true);

        var template = this.getTemplatesGrid().selModel.selected.get(0);
        template.set('attributes', attributes);

        this.saveTemplate(template);
    },

    saveTemplate: function(template) {
        var loadCallback = Ext.bind(function() {
            this.loadTemplateAttributes(template);
        }, this)

        Ext.Ajax.request({
            url: Slims.Url.getRoute('settemplate'),
            method: 'POST',
            jsonData: template.getData(),
            scope: this,
            success: function() {
                this.getAttributesGrid().setLoading(false);
                this.getTemplatesGrid().setLoading(false);

                this.getTemplatesGrid().getStore().load(loadCallback);
            },
            failure: function() {
                this.getAttributesGrid().setLoading(false);
                this.getTemplatesGrid().setLoading(false);

                this.getTemplatesGrid().getStore().load(loadCallback);
            }
        });
    },

    createTemplate: function(template) {

    },

    onSaveTemplateClick: function() {

    },

    saveAttribute: function() {

    }
});
