Ext.define('Slims.controller.Templates', {
    extend: 'Ext.app.Controller',

    views: [
        'templates.Panel',
        'templates.TemplateWindow'
    ],

    stores: ['Templates', 'Attributes'],
    models: ['Template', 'Attribute'],

    refs: [{
        ref: 'templatesGrid',
        selector: 'templatesgrid'
    }],

    init: function() {
        this.control({
            'templatesgrid': {

            },
            'templatesgrid button[name=addTemplate]': {
                click: this.addTemplate
            }
        });
    },

    addTemplate: function() {
        var window = Ext.create('Slims.view.templates.TemplateWindow');

        window.show();
    }
});
