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
        dialog.setLoading('Please, wait...');

        var url;
        if (container.getId()) {
            url = Ext.String.format(Slims.Url.getRoute('setcontainer'), container.getId());
        } else {
            url = Slims.Url.getRoute('createcontainer');
        }
        Ext.Ajax.request({
            url: url,
            method: 'POST',
            params: this.extractContainerData(container),
            scope: this,
            success: function(xhr) {
                var response = Ext.decode(xhr.responseText);
                dialog.setLoading(false);
                dialog.close();
                this.reloadGrid();
            },
            failure: function() {
                dialog.setLoading(false);
            }
        })

    },

    extractContainerData: function(container) {
        var allData = container.data,
            trueData = {};

        trueData = {
            name: allData.name,
            comment: allData.comment,
            colour: allData.colour
        }

        // if create mode
        if (!container.data.id) {
            Ext.apply(trueData, {
                parent: allData.parent,
                research_group: allData.research_group,
                rows: allData.rows,
                columns: allData.columns,
                stores: allData.stores
            });
        }

        return trueData;
    },

    reloadGrid: function() {
        this.getContainersGrid().getStore().reload();
    }
});
