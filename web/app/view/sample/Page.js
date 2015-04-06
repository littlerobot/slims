Ext.define('Slims.view.sample.Page', {
    extend: 'Ext.panel.Panel',
    xtype: 'samplespage',

    layout: 'card',
    border: false,

    requires: [],

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            bodyStyle: 'background: yellow;',
            buttons: ['->', {
                text: 'Next',
                handler: this.nextTab,
                scope: this
            }]
        }, {
            xtype: 'panel',
            bodyStyle: 'background: green;',
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Next',
                handler: this.nextTab,
                scope: this
            }]
        }, {
            xtype: 'panel',
            bodyStyle: 'background: red;',
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Finish',
                handler: this.commitChanges,
                scope: this
            }]
        }];

        this.callParent();
    },

    nextTab: function() {
        this.layout.setActiveItem(this.layout.getNext());
    },

    prevTab: function() {
        this.layout.setActiveItem(this.layout.getPrev());
    },

    commitChanges: function() {

    }
});