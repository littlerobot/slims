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
            'attributesgrid button[name=reloadGrid]': {
                click: this.reloadGrids
            },
            'attributesgrid': {
                editrecord: this.editAttribute,
                attributeschanged: this.commitAttributes
            },
            'templatewindow': {
                save: this.saveTemplate
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
            attribute: attribute
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

    saveTemplate: function(template, dialog) {
        var loadCallback = Ext.bind(function() {
                this.loadTemplateAttributes(template);
            }, this),
            url;

        if (template.getId()) {
            url = Ext.String.format(Slims.Url.getRoute('settemplate'), template.getId());
        } else {
            url = Slims.Url.getRoute('createtemplate');
        }

        if (dialog) dialog.setLoading(true);

        Ext.Ajax.request({
            url: url,
            method: 'POST',
            jsonData: {
                name: template.get('name'),
                attributes: template.get('attributes') || []
            },
            scope: this,
            success: function() {
                if (dialog) {
                    dialog.setLoading(false);
                    dialog.close();
                }
                this.getAttributesGrid().setLoading(false);
                this.getTemplatesGrid().setLoading(false);

                this.reloadGrids();
            },
            failure: function() {
                if (dialog) dialog.setLoading(false);
                this.getAttributesGrid().setLoading(false);
                this.getTemplatesGrid().setLoading(false);

                this.reloadGrids();
            }
        });
    },

    reloadGrids: function() {
        var template = this.getTemplatesGrid().selModel.selected.get(0),
            loadCallback = Ext.bind(function() {
                if (template)
                    this.loadTemplateAttributes(template);
            }, this);

        this.getTemplatesGrid().getStore().load(loadCallback);
    },

    saveAttribute: function(attribute, dialog) {
        dialog.close();

        if (attribute.getId()) {
            attribute.commit();
        } else {
            this.getAttributesGrid().getStore().add(attribute);
        }
        var attributes = this.getAttributesGrid().getStore().data;

        this.getAttributesGrid().updateAttributesOrder(attributes);
    }
});
