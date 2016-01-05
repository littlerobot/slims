Ext.define('Slims.view.sample.wizard.PositionsPanel', {
    extend: 'Ext.panel.Panel',
    xtype: 'positionsview',

    layout: {
        type: 'vbox',
        align: 'center'
    },
    selectedContainer: null,
    items: [],
    autoScroll: true,

    initComponent: function() {
        this.on('show', this.getContainerPositions, this);
        this.callParent(arguments);
    },

    getContainerPositions: function() {
        this.setLoading('Loading...');

        Ext.Ajax.request({
            url: Slims.Url.getRoute('getcontainerpositions', [this.selectedContainer.get('id')]),
            method: 'GET',
            scope: this,
            success: function(xhr) {
                this.setLoading(false);
                var response = Ext.decode(xhr.responseText);
                var samples = response.samples;
                this.setSamplesData(response.samples);
            },
            failure: function() {
                this.setLoading(false);
                this.setSamplesError();
            }
        });
    },

    setSamplesError: function() {
        this.removeAll();
        this.items.add(Ext.create('Ext.form.Label', {
            padding: 10,
            width: '100%',
            html: '<center style="color: red;">Error occured.</center>'
        }));
        this.doLayout();
    },

    setSamplesData: function(samples) {
        this.removeAll();
        this.items.add(Ext.create('Ext.form.Label', {
            padding: 10,
            width: '100%',
            html: 'Select empty positions to store samples'
        }));

        this.items.add(Ext.create('Ext.form.Panel', {
            name: 'containerPositions',
            style: 'padding-top: 10px;',
            items: this.getPositionsItems(samples)
        }));

        this.doLayout();
    },

    getSampleName: function (sample) {
        for (var i = 0; i < sample.attributes.length; i++) {
            var attribute = sample.attributes[i];

            if (attribute.label === 'Sample/cell line name') {
                return attribute.value;
            }
        }

        return 'Sample has an unknown name';
    },

    getPositionsItems: function(samples) {
        var colsItems = [],
            columnsCount = this.selectedContainer.get('columns'),
            rowsCount = this.selectedContainer.get('rows');

        for (var i = 0; i < rowsCount; i++) {
            var items = [];

            for (var j = 0; j < columnsCount; j++) {
                var positionId = i+':'+j,
                    sample = samples[positionId], cb, tipHtml = null;

                if (sample) {
                    tipHtml = [
                        '<b>' + this.getSampleName(sample) + '</b>',
                        '<b><i>Type:</i></b> ' + sample.type.name,
                    ].join('<br />');
                } else {
                    cb = Ext.create('Ext.form.field.Checkbox',  {
                        name: positionId,
                        inputValue: true,
                        hideLabel: true,
                        tooltip: positionId,
                        componentCls: 'slims-wizard-position-cb',
                        fieldStyle: {
                            'margin-top': '6px',
                            'margin-left': '0px'
                        },
                        listeners: {
                            change: function(cb, val) {
                                this.fireEvent('positionselected', cb.name, val);
                            },
                            scope: this
                        }
                    });
                }

                items.push({
                    xtype: 'container',
                    border: true,
                    items: sample ? [] : [cb],
                    layout: {
                        type: 'vbox',
                        align: 'center',
                        pack: 'middle'
                    },
                    height: 30,
                    width: 30,
                    margin: 3,
                    tip: tipHtml,
                    style: {
                        'background-color': sample ? sample.colour : 'white',
                        'border': sample ? '1px solid darkred' : '1px solid lightgray'
                    },
                    listeners: {
                        render: function(c) {
                            if (c.tip) {
                                Ext.create('Ext.tip.ToolTip', {
                                    target: c.getEl(),
                                    html: c.tip
                                });
                            }
                        }
                    }
                });
            }

            var rowPanel = Ext.create('Ext.panel.Panel', {
                layout: 'hbox',
                items: items
            });
            colsItems.push(rowPanel);
        }

        return colsItems;
    },

    getValue: function() {
        var values = this.down('form').getValues();
        if (Ext.Object.toQueryString(values).length > 0) {
            return values;
        } else {
            return false;
        }
    }
});
