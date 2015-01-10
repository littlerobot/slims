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
                click: this.addTemplate
            },
            'attributesgrid button[name=addAttribute]': {
                click: this.addAttribute
            }
        });
    },

    onTemplateSelect: function(selModel, template) {
        this.getAddAttributeButton().setDisabled(false);
        this.getAttributesGrid().getStore().loadData(template.get('attributes'));
    },

    addTemplate: function() {
        var window = Ext.create('Slims.view.templates.TemplateWindow');

        window.show();
    },

    addAttribute: function() {
        var window = Ext.create('Slims.view.templates.AttributeWindow');

        window.show();
    }
});
