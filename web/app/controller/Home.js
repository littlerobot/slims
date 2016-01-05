Ext.define('Slims.controller.Home', {
    extend: 'Ext.app.Controller',

    models: ['Container'],
    stores: ['Containers'],
    views: [
        'home.container.Window',
        'sample.EditAttributesWindow'
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
            'positionsgrid actioncolumn': {
                editrecord: this.editPositionSample
            },
            'positionsview': {
                positionselected: this.onPositionSelected
            },
            'samplewizard': {
                save: this.buildPositionsStoreAttributes
            },
            'homepage [name=storeSamples]': {
                click: this.storeSamples
            },
            'editattributeswindow': {
                save: this.saveSampleAttributes
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
        dialog.setLoading('Saving. Please wait.');

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
            researchGroupId = null,
            trueData = {
                parent: allData.parent,
                name: allData.name,
                comment: allData.comment,
                colour: allData.colour
            };

        if (null !== allData.owner) {
            researchGroupId = allData.research_group.hasOwnProperty("id") ? allData.research_group.id : allData.researchGroup;

            Ext.apply(trueData, {
               research_group: researchGroupId
            });
        }

        // if create mode
        if (!container.data.id) {
            Ext.apply(trueData, {
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

        this.getPositionsGrid().reset();
        this.getDetailsPanel().setDisabled(true);
        if (selectedContainer.get('stores') == 'samples') {
            positionsMap.selectedContainer = selectedContainer;
            positionsMap.fireEvent('show');
            this.getDetailsPanel().setDisabled(false);
        }

        this.getPositionsGrid().buildStoreAttributes();
    },

    onPositionSelected: function(positionId, selected) {
        if (selected) {
            var position = Ext.create('Slims.model.sample.Sample', {
                positionId: positionId
            });
            this.getPositionsGrid().getStore().add(position);
            this.getPositionsGrid().refreshStoreAttributes();
        } else {
            var id = this.getPositionsGrid().getStore().find('positionId', positionId);
            var record = this.getPositionsGrid().getStore().getAt(id);
            if (record) {
                this.getPositionsGrid().getStore().remove(record);
            }
        }
    },

    buildPositionsStoreAttributes: function(data) {
        this.getPositionsGrid().buildStoreAttributes(data);
    },

    storeSamples: function() {
        var currentContainer = this.getContainersGrid().selModel.selected.get(0),
            configured = this.getPositionsGrid().configured,
            samplesGridData = this.getPositionsGrid().getStore().data.items;

        if (!currentContainer || !samplesGridData.length) {
            Ext.Msg.alert('No data to save', 'Please select positions in container and configure them before saving.');
            return;
        }

        if (!configured) {
            Ext.Msg.alert('Samples not configured', 'Please configure samples before saving.');
            return;
        }

        var containerId = currentContainer.getId();

        var samples = samplesGridData.map(function(sample) {
            var positionId = sample.get('positionId'),
                position = positionId.split(':'),
                row = position[0],
                column = position[1],
                colour = sample.get('samplesColor'),
                attributes = [];

            for (var name in sample.data) {
                if (name.indexOf('attributes.id') == 0) {
                    var id = name.replace('attributes.id', ''),
                        value = sample.data[name],
                        attr;
                    if (value.file) {
                        attr = {
                            id: parseInt(id),
                            filename: value.name,
                            value: value.file
                        };
                    } else {
                        attr = {
                            id: parseInt(id),
                            value: sample.data[name]
                        };
                    }
                    attributes.push(attr);
                }
            }

            return {
                type: sample.get('sampleType'),
                template: sample.get('sampleInstanceTemplate'),
                colour: colour,
                row: parseInt(row),
                column: parseInt(column),
                attributes: attributes
            };
        });

        this.getPositionsGrid().setLoading('Saving. Please wait.');

        Ext.Ajax.request({
            url: Slims.Url.getRoute('setsamples', [containerId]),
            method: 'POST',
            jsonData: {samples: samples},
            scope: this,
            success: function(xhr) {
                this.getPositionsGrid().reset();
                this.getPositionsGrid().setLoading(false);
                this.getPositionsMap().fireEvent('show');
            },
            failure: function() {
                this.getPositionsGrid().setLoading(false);
            }
        });
    },

    editPositionSample: function(sample) {
        if (!this.getPositionsGrid().configured) {
            Ext.Msg.alert('Samples not configured', 'Please configure samples before editing.');
            return;
        }

        var editSampleWindow = Ext.create('Slims.view.sample.EditAttributesWindow', {
            mode: 'store',
            sample: sample
        });

        editSampleWindow.show();
    },

    saveSampleAttributes: function(values) {
        this.getPositionsGrid().view.refresh();
    }
});
