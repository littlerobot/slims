Ext.define('Slims.controller.SampleTemplates', {
    extend: 'Ext.app.Controller',

    views: [
        'sample.templates.Panel',
        'sample.templates.TemplateWindow',
        'sample.templates.AttributeWindow'
    ],

    stores: ['sample.Templates', 'sample.Attributes', 'sample.AttributeTypes'],
    models: ['sample.Template', 'sample.Attribute'],

    refs: [{
        ref: 'templatesGrid',
        selector: 'sampletemplatesgrid'
    }, {
        ref: 'attributesGrid',
        selector: 'sampleattributesgrid'
    }, {
        ref: 'addAttributeButton',
        selector: 'sampleattributesgrid button[name=addAttribute]'
    }],

    init: function() {
        this.control({
            'sampletemplatesgrid': {
                select: this.onTemplateSelect,
                editrecord: this.openEditTemplateWindow
            },
            'sampletemplatesgrid button[name=addTemplate]': {
                click: this.openAddTemplateWindow
            },
            'sampleattributesgrid button[name=addAttribute]': {
                click: this.openAddAttributeWindow
            },
            'sampletemplatesgrid button[name=reloadGrid]': {
                click: this.reloadGrids
            },
            'sampleattributesgrid': {
                editrecord: this.editAttribute,
                attributeschanged: this.commitAttributes
            },
            'sampletemplatewindow': {
                save: this.saveTemplate
            },
            'sampleattributewindow': {
                save: this.saveAttribute
            }
        });

        this.createAttributeTypesStore();
    },

    createAttributeTypesStore: function() {
        Ext.create('Slims.store.sample.AttributeTypes', {
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
        var window = Ext.create('Slims.view.sample.templates.TemplateWindow');

        window.show();
    },

    openEditTemplateWindow: function(template) {
        var window = Ext.create('Slims.view.sample.templates.TemplateWindow', {
            record: template
        });

        window.show();
    },

    openAddAttributeWindow: function() {
        var window = Ext.create('Slims.view.sample.templates.AttributeWindow', {
            usedLabels: this.getUsedAttrLabels()
        });

        window.show();
    },

    editAttribute: function(attribute) {
        var window = Ext.create('Slims.view.sample.templates.AttributeWindow', {
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

        var jsonData = {
            name: template.get('name')
        }

        if (template.getId()) {
            url = Ext.String.format(Slims.Url.getRoute('setsampletemplate'), template.getId());

            // remove extra fields before request
            Ext.each(template.get('attributes'), function(attribute, index) {
                attribute.order = index + 1;
                if (attribute.type != 'option')
                    delete attribute.options;

                delete attribute.id;

                attributes.push(attribute);
            });
            if (attributes.length) {
                jsonData.attributes = attributes;
            }
        } else {
            url = Slims.Url.getRoute('createsampletemplate');
        }

        if (dialog) dialog.setLoading(true);

        Ext.Ajax.request({
            url: url,
            method: 'POST',
            jsonData: jsonData,
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
