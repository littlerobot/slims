Ext.define('Slims.controller.Home', {
    extend: 'Ext.app.Controller',

    models: ['Container'],
    stores: ['Containers'],
    views: [
        'home.container.Window'
    ],

    refs: [{
        ref: 'containersGrid',
        selector: 'containersgrid'
    }],

    init: function() {
        this.control({
            'containersgrid button[name=addContainer]': {
                click: this.openAddContainerWindow
            },
            'containersgrid button[name=reloadGrid]': {
                click: this.reloadGrid
            },
            'containersgrid actioncolumn': {
                editrecord: this.openEditUserWindow
            },
            'containerwindow': {
                save: this.saveContainer
            }
        });
    },

    openAddContainerWindow: function() {
        var addContainerWindow = Ext.create('Slims.view.home.container.Window');

        addContainerWindow.show();
    },

    openEditUserWindow: function(container) {
        var editContainerWindow = Ext.create('Slims.view.home.container.Window', {
            record: container
        });

        editContainerWindow.show();
    },

    saveContainer: function(container, dialog) {

        // Ext.Ajax.request({
        //     url: '',
        //     scope: this,
        //     success: function() {},
        //     failure: function() {}
        // })

    },

    reloadGrid: function() {
        this.getContainersGrid().getStore().reload();
    }
});
