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

        this.getPositionsGrid().buildStoreAttributes();
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

    buildPositionsStoreAttributes: function(data) {
        this.getPositionsGrid().buildStoreAttributes(data);
    },

    storeSamples: function() {
        // {
        //     "container": 5,
        //     "row": 7,
        //     "column": 7,
        //     "colour": null,
        //     "attributes": [
        //       {
        //         "sample_instance_attribute": 12,
        //         "value": "Los Angeles"
        //       },
        //       {
        //         "sample_instance_attribute": 1,
        //         "filename": "Manufacturer information.pdf",
        //         "mime_type": "application/pdf",
        //         "value": "<base 64 encoded file content>"
        //       }
        //     ]
        //   },
        //   {
        //     "container": 5,
        //     "row": 7,
        //     "column": 8,
        //     "colour": "#36f23a",
        //     "attributes": [
        //       {
        //         "sample_instance_attribute": 12,
        //         "value": "New York"
        //       },
        //       {
        //         "sample_instance_attribute": 1,
        //         "filename": "Manufacturer information.pdf",
        //         "mime_type": "application/pdf",
        //         "value": "<base 64 encoded file content>"
        //       }
        //     ]
        //   },
        //   {
        //     ...
        //   }

        var samplesData = this.getPositionsGrid().getStore().data.items,
            containerId = this.getContainersGrid().selModel.selected[0].getId();

        var samples = samplesData.map(function(sample) {
            var positionId = sample.get('positionId'),
                position = positionId.split(':'),
                row = position[0],
                column = position[1],
                colour = sample.get('sampleColor'),
                attributes = sample.data;
            // {
            //     sample_instance_attribute:
            //     value:
            // }
            return {
                container: containerId,
                colour: colour,
                row: row,
                column: column,
                attributes: []
            }

        })



        Ext.Ajax.request({
            url: Slims.Url.getRoute('setsamples'),
            method: 'POST',
            params: {
                sample_template_id: wizard.sampleTemplate,
                sample_instance_id: wizard.sampleInstanceId,
                container_id: wizard.selectedContainer,
                store_attributes: storeAttributes,
                positions: wizard.positionsMap,
                colour: colour
            },
            scope: this,
            success: function(xhr) {
                wizard.setLoading(false);
                this.getGrid().getStore().load();
                wizard.close();
            },
            failure: function() {
                wizard.setLoading(false);
            }
        });
    },

    editPositionSample: function(sample) {
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
