Ext.define('Slims.controller.InstanceTemplates', {
    extend: 'Ext.app.Controller',

    views: [
        'sample.instancetemplates.Panel',
        'sample.instancetemplates.TemplateWindow',
        'sample.instancetemplates.AttributeWindow'
    ],

    stores: ['sample.InstanceTemplates', 'sample.Attributes', 'sample.AttributeTypes'],
    models: ['sample.InstanceTemplate', 'sample.Attribute'],

    refs: [{
        ref: 'tab',
        selector: 'instancetemplatespage'
    }, {
        ref: 'templatesGrid',
        selector: 'instancetemplatesgrid'
    }, {
        ref: 'storeAttributesGrid',
        selector: 'instancetemplatesattributes[name=storeAttributes]'
    }, {
        ref: 'removeAttributesGrid',
        selector: 'instancetemplatesattributes[name=removeAttributes]'
    }, {
        ref: 'addAttributeButton',
        selector: 'instancetemplatesattributes button[name=addAttribute]'
    }],

    init: function() {
        this.control({
            'instancetemplatesgrid': {
                select: this.onTemplateSelect,
                editrecord: this.openEditTemplateWindow
            },
            'instancetemplatesgrid button[name=addTemplate]': {
                click: this.openAddTemplateWindow
            },
            'instancetemplatesattributes button[name=addAttribute]': {
                click: this.openAddAttributeWindow
            },
            'instancetemplatesgrid button[name=reloadGrid]': {
                click: this.reloadGrids
            },
            'instancetemplatesattributes': {
                afterrender: this.setRestoreSelectionListener,
                editrecord: this.editAttribute,
                attributeschanged: this.commitAttributes
            },
            'instancetemplatewindow': {
                save: this.saveTemplate
            },
            'instancetemplatesattrwindow': {
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

        var instanceTemplatesStore = Ext.create('Slims.store.sample.InstanceTemplates', {
            storeId: 'instanceTemplates'
        });

        instanceTemplatesStore.load();
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
        var window = Ext.create('Slims.view.sample.instancetemplates.TemplateWindow');

        window.show();
    },

    openEditTemplateWindow: function(template) {
        var window = Ext.create('Slims.view.sample.instancetemplates.TemplateWindow', {
            record: template
        });

        window.show();
    },

    openAddAttributeWindow: function(button) {
        var grid = button.up('grid');

        var window = Ext.create('Slims.view.sample.instancetemplates.AttributeWindow', {
            grid: grid,
            usedLabels: this.getUsedAttrLabels(grid)
        });

        window.show();
    },

    editAttribute: function(attribute, grid) {
        var window = Ext.create('Slims.view.sample.instancetemplates.AttributeWindow', {
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

        var jsonData = {
                name: template.get('name')
            }, url;

        if (template.getId()) {
            url = Slims.Url.getRoute('setsampleinstancetemplate', [template.getId()]);

            jsonData.remove = this.prepareAttributeParams(template.get('remove'));
            jsonData.store  = this.prepareAttributeParams(template.get('store'));
        } else {
            url = Slims.Url.getRoute('createsampleinstancetemplate');
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
            delete attribute.activity;
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