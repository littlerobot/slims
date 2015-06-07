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
    }, {
        ref: 'positionsMap',
        selector: '[name=details] > positionsview'
    }, {
        ref: 'detailsPanel',
        selector: '[name=details]'
    }, {
        ref: 'positionsGrid',
        selector: 'positionsgrid'
    }, {
        ref: 'wizard',
        selector: 'samplewizard'
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
            'containersgrid': {
                select: this.loadPositionsMap
            },
            'containerwindow': {
                save: this.saveContainer
            },
            'positionsgrid': {
                configure: this.openWizard
            },
            'positionsview': {
                positionselected: this.onPositionSelected
            },
            'samplewizard [name=commit]': {
                click: this.setAttributes
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

    openWizard: function() {
        Ext.create('Slims.view.sample.wizard.Wizard').show();
    },

    saveContainer: function(container, dialog) {
        dialog.setLoading('Please, wait...');

        var url;
        if (container.getId()) {
            url = Slims.Url.getRoute('setcontainer', [container.getId()]);
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
                var parentPath = container.get('parentPath');
                this.reloadGrid(parentPath);
            },
            failure: function() {
                dialog.setLoading(false);
            }
        });
    },

    extractContainerData: function(container) {
        var allData = container.data,
            trueData = {
                name: allData.name,
                comment: allData.comment,
                colour: allData.colour
            };

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

    reloadGrid: function(path) {
        this.getContainersGrid().reload(path);
    },

    loadPositionsMap: function(grid, record, index) {
        var positionsMap = this.getPositionsMap(),
            selectedContainer = record;

        this.getPositionsGrid().getStore().removeAll();
        this.getDetailsPanel().setDisabled(true);
        if (selectedContainer.get('stores') == 'samples') {
            positionsMap.selectedContainer = selectedContainer;
            positionsMap.fireEvent('show');
            this.getDetailsPanel().setDisabled(false);
        }
    },

    onPositionSelected: function(positionId, selected) {
        if (selected) {
            var position = Ext.create('Slims.model.sample.Sample', {
                positionId: positionId
            });
            this.getPositionsGrid().getStore().add(position);
        } else {
            var id = this.getPositionsGrid().getStore().find('positionId', positionId);
            var record = this.getPositionsGrid().getStore().getAt(id);
            if (record) {
                this.getPositionsGrid().getStore().remove(record);
            }
        }
    },

    setAttributes: function() {
        var wizard = this.getWizard(),
            fields = wizard.down('storeattributesform').down('fieldset').items.items,
            storeAttrColumns = fields.map(function(field) {
                return {
                    text: field.fieldLabel,
                    value: field.getValue(),
                    dataIndex: field.name
                };
            });

        this.getPositionsGrid().reconfigure
    }
});
