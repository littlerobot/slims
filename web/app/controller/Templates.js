Ext.define('Slims.controller.Templates', {
    extend: 'Ext.app.Controller',

    views: ['templates.Panel'],
    stores: ['Templates'],
    models: ['Template'],

    // refs: [{
    //     ref: 'usersGrid',
    //     selector: 'usersgrid'
    // }],

    init: function() {
        this.control();
    }
});
