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
        ref: 'tab',
        selector: 'sampletemplatespage'
    }, {
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

        this.createStores();
    },

    createStores: function() {
        var templatesStore = Ext.create('Slims.store.Templates', {
            storeId: 'templates'
        });

        templatesStore.load();
    },

    setRestoreSelectionListener: function() {
        Ext.each([this.getStoreAttributesGrid(), this.getRemoveAttributesGrid()], function(grid) {
            grid.getStore().on('load', function() { grid.selModel.select(grid.selModel.lastSelected); });
        }, this)
    },

    onTemplateSelect: function(selModel, template) {
        this.loadTemplateAttributes(template);
    },

    loadTemplateAttributes: function(template) {
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
            usedLabels: this.getUsedAttrLabels(grid)
        });

        window.show();
    },

    getUsedAttrLabels: function(grid) {
        var labels = [],
            attributes = grid.getStore().data.items;

        for (var i in attributes) labels.push(attributes[i].get('label'));

        return labels;
    },

    commitAttributes: function() {
        var storeAttributesItems = this.getStoreAttributesGrid().getStore().data.items,
            removeAttributesItems = this.getRemoveAttributesGrid().getStore().data.items,
            storeAttributes = [],
            removeAttributes = [];

        for (var i in removeAttributesItems) removeAttributes.push(removeAttributesItems[i].data);
        for (var i in storeAttributesItems ) storeAttributes .push(storeAttributesItems [i].data);

        var template = this.getTemplatesGrid().selModel.selected.get(0);

        template.set('store',  storeAttributes);
        template.set('remove', removeAttributes);

        this.saveTemplate(template);
    },

    saveTemplate: function(template, dialog) {
        this.getTab().setLoading('Saving. Please, wait...');
        var url,
            attributes = [];

        var jsonData = {
            name: template.get('name')
        };

        if (template.getId()) {
            url = Ext.String.format(Slims.Url.getRoute('setsampletemplate'), template.getId());

            jsonData.store  = this.prepareAttributeParams(template.get('store' ));
            jsonData.remove = this.prepareAttributeParams(template.get('remove'));
        } else {
            url = Slims.Url.getRoute('createsampletemplate');
        }

        if (dialog) {
            dialog.setLoading(true);
        }

        Ext.Ajax.request({
            url: url,
            method: 'POST',
            jsonData: jsonData,
            scope: this,
            success: function() {
                this.getTab().setLoading(false);
                if (dialog) {
                    dialog.setLoading(false);
                    dialog.close();
                }

                this.reloadGrids();
            },
            failure: function() {
                this.getTab().setLoading(false);
                if (dialog) {
                    dialog.setLoading(false);
                }

                this.reloadGrids();
            }
        });
    },

    prepareAttributeParams: function(attributes) {
        var attributes = Ext.clone(attributes);
        Ext.each(attributes, function(attribute, index) {
            attribute.order = index + 1;
            if (attribute.type != 'option')
                delete attribute.options;

            delete attribute.id;
        });
        return attributes;
    },

    reloadGrids: function() {
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
