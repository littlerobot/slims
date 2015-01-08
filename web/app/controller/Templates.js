Ext.define('Slims.controller.Templates', {
    extend: 'Ext.app.Controller',

    views: ['templates.Panel'],
    stores: ['Templates', 'Attributes'],
    models: ['Template', 'Attribute'],

    // refs: [{
    //     ref: 'usersGrid',
    //     selector: 'usersgrid'
    // }],

    init: function() {
        this.control();
    }
});
