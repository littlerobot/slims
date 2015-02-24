Ext.define('Slims.controller.SampleTypes', {
    extend: 'Ext.app.Controller',

    views: [
        'sample.types.Panel',
        'sample.types.Grid'
    ],

    stores: ['sample.Types'],
    models: ['sample.Type'],

    refs: [],

    init: function() {
        this.control({});
    }
});
