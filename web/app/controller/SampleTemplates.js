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
        ref: 'storeAttributesGrid',
        selector: 'sampleattributesgrid[name=storeAttributes]'
    }, {
        ref: 'removeAttributesGrid',
        selector: 'sampleattributesgrid[name=removeAttributes]'
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
                afterrender: this.setRestoreSelectionListener,
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

        // this.createAttributeTypesStore();
    },

    setRestoreSelectionListener: function() {
        Ext.each([this.getStoreAttributesGrid(), this.getRemoveAttributesGrid()], function(grid) {
            grid.getStore().on('load', function() { grid.selModel.select(grid.selModel.lastSelected); });
        }, this)
    },

    // createAttributeTypesStore: function() {
    //     Ext.create('Slims.store.sample.AttributeTypes', {
    //         storeId: 'attributeTypes'
    //     });
    // },

    onTemplateSelect: function(selModel, template) {
        this.loadTemplateAttributes(template);
    },

    loadTemplateAttributes: function(template, attribute) {
        this.getRemoveAttributesGrid().down('button[name=addAttribute]').setDisabled(false);
        this.getStoreAttributesGrid().down('button[name=addAttribute]').setDisabled(false);


        this.getRemoveAttributesGrid().down('actioncolumn').setVisible(template.get('editable'));
        this.getStoreAttributesGrid().down('actioncolumn').setVisible(template.get('editable'));

        this.loadAttributes(template);
    },

    loadAttributes: function(template) {
        var removeAttributes = template.get('remove') || [],
            storeAttributes = template.get('store') || [];

        this.getRemoveAttributesGrid().getStore().loadData(removeAttributes);
        this.getStoreAttributesGrid().getStore().loadData(storeAttributes);
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

    openAddAttributeWindow: function(button) {
        var grid = button.up('grid');

        var window = Ext.create('Slims.view.sample.templates.AttributeWindow', {
            grid: grid,
            usedLabels: this.getUsedAttrLabels(grid)
        });

        window.show();
    },

    editAttribute: function(attribute, grid) {
        var window = Ext.create('Slims.view.sample.templates.AttributeWindow', {
            attribute: attribute,
            grid: grid,
            usedLabels: this.getUsedAttrLabels()
        });

        window.show();
    },

    getUsedAttrLabels: function() {
        var labels = [],
        storeAttributes = this.getStoreAttributesGrid().getStore().data.items,
        removeAttributes = this.getRemoveAttributesGrid().getStore().data.items;

        for (var i in storeAttributes ) labels.push(storeAttributes [i].get('label'));
        for (var i in removeAttributes) labels.push(removeAttributes[i].get('label'));

        return labels;
    },

    commitAttributes: function() {
        // сделать маску на всю страницу
        // grid.getTab.setLoadig(true);
        this.getTemplatesGrid().setLoading(true);

        var storeAttributes = this.getStoreAttributesGrid().getStore().data.items,
            removeAttributes = this.getRemoveAttributesGrid().getStore().data.items,
            attributes = {
                store: [],
                remove: []
            };

        for (var i in storeAttributes ) attributes.store .push(storeAttributes [i].data);
        for (var i in removeAttributes) attributes.remove.push(removeAttributes[i].data);

        var template = this.getTemplatesGrid().selModel.selected.get(0);
        template.set('store', attributes.store);
        template.set('remove', attributes.remove);

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

            var prepareAttributeParams = function(attributes) {
                Ext.each(attributes, function(attribute, index) {
                    attribute.order = index + 1;
                    if (attribute.type != 'option')
                        delete attribute.options;

                    delete attribute.id;
                });
                return attributes;
            };

            jsonData.store  = prepareAttributeParams(template.get('store') );
            jsonData.remove = prepareAttributeParams(template.get('remove'));
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
                // this.getAttributesGrid().setLoading(false);
                this.getTemplatesGrid().setLoading(false);

                this.reloadGrids();
            },
            failure: function() {
                if (dialog) dialog.setLoading(false);
                // this.getAttributesGrid().setLoading(false);
                this.getTemplatesGrid().setLoading(false);

                this.reloadGrids();
            }
        });
    },

    reloadGrids: function() {
        // var selectedTemplate = this.getTemplatesGrid().selModel.selected.get(0),
        //     selectedAttribute = this.getAttributesGrid().selModel.selected.get(0);
        //     loadCallback = Ext.bind(function() {
        //         if (selectedTemplate)
        //             this.loadTemplateAttributes(selectedTemplate, selectedAttribute);
        //     }, this);

        // this.getTemplatesGrid().getStore().load(loadCallback);

        this.getTemplatesGrid().getStore().load();
    },

    saveAttribute: function(attribute, dialog, grid) {
        dialog.close();

        if (attribute.getId()) {
            attribute.commit();
        } else {
            grid.getStore().add(attribute);
        }
        var attributes = grid.getStore().data;

        grid.updateAttributesOrder(attributes);
    }
});
