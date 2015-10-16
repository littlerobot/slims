Ext.define('Slims.controller.TypeTemplates', {
    extend: 'Ext.app.Controller',

    views: [
        'sample.typetemplates.Panel',
        'sample.typetemplates.TemplateWindow',
        'sample.typetemplates.AttributeWindow'
    ],

    stores: ['Templates', 'Attributes', 'AttributeTypes'],
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
                select: this.onTemplateSelect,
                editrecord: this.openEditTemplateWindow
            },
            'templatesgrid button[name=addTemplate]': {
                click: this.openAddTemplateWindow
            },
            'attributesgrid button[name=addAttribute]': {
                click: this.openAddAttributeWindow
            },
            'templatesgrid button[name=reloadGrid]': {
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

        this.createAttributeTypesStore();
    },

    createAttributeTypesStore: function() {
        Ext.create('Slims.store.AttributeTypes', {
            storeId: 'attributeTypes'
        });
    },

    onTemplateSelect: function(selModel, template) {
        this.loadTemplateAttributes(template);
    },

    loadTemplateAttributes: function(template, attribute) {
        this.getAddAttributeButton().setDisabled(false);
        this.getAttributesGrid().getStore().on('load', function() {
            if (attribute) {
                var sm = this.getAttributesGrid().selModel;
                sm.select(sm.lastSelected);
            }
        }, this, {single: true});

        this.getAttributesGrid().down('actioncolumn').setVisible(template.get('editable'));
        this.getAttributesGrid().getStore().loadData(template.get('attributes'));
    },

    openAddTemplateWindow: function() {
        var window = Ext.create('Slims.view.sample.typetemplates.TemplateWindow');

        window.show();
    },

    openEditTemplateWindow: function(template) {
        var window = Ext.create('Slims.view.sample.typetemplates.TemplateWindow', {
            record: template
        });

        window.show();
    },

    openAddAttributeWindow: function() {
        var window = Ext.create('Slims.view.sample.typetemplates.AttributeWindow', {
            usedLabels: this.getUsedAttrLabels()
        });

        window.show();
    },

    editAttribute: function(attribute) {
        var window = Ext.create('Slims.view.sample.typetemplates.AttributeWindow', {
            attribute: attribute,
            usedLabels: this.getUsedAttrLabels()
        });

        window.show();
    },

    getUsedAttrLabels: function() {
        var labels = [],
        attributes = this.getAttributesGrid().getStore().data.items;

        for (var i in attributes) labels.push(attributes[i].get('label'));

        return labels;
    },

    commitAttributes: function(attributes) {
        this.getAttributesGrid().setLoading(true);
        this.getTemplatesGrid().setLoading(true);

        var template = this.getTemplatesGrid().selModel.selected.get(0);
        template.set('attributes', attributes);

        this.saveTemplate(template);
    },

    saveTemplate: function(template, dialog) {
        var url,
            attributes = [];

        if (template.getId()) {
            url = Slims.Url.getRoute('settemplate', [template.getId()]);

            // remove extra fields before request
            Ext.each(template.get('attributes'), function(attribute, index) {
                attribute.order = index + 1;
                if (attribute.type != 'option')
                    delete attribute.options;

                delete attribute.id;

                attributes.push(attribute);
            });
        } else {
            url = Slims.Url.getRoute('createtemplate');
        }

        if (dialog) dialog.setLoading(true);

        Ext.Ajax.request({
            url: url,
            method: 'POST',
            jsonData: {
                name: template.get('name'),
                attributes: attributes || []
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
        var selectedTemplate = this.getTemplatesGrid().selModel.selected.get(0),
            selectedAttribute = this.getAttributesGrid().selModel.selected.get(0);
            loadCallback = Ext.bind(function() {
                if (selectedTemplate)
                    this.loadTemplateAttributes(selectedTemplate, selectedAttribute);
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
