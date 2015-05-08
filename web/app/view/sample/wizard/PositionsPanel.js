Ext.define('Slims.view.sample.wizard.PositionsPanel', {
    extend: 'Ext.panel.Panel',
    xtype: 'positionsview',

    layout: {
        type: 'vbox',
        align: 'center'
    },
    containerId: null,

    initComponent: function() {
        this.on('show', this.buildItems, this);
        this.callParent(arguments);
    },

    buildItems: function() {
        this.setLoading('Loading...');
        this.removeAll();
        Ext.Ajax.request({
            url: Slims.Url.getRoute('getcontainerpositions', [this.containerId]),
            method: 'GET',
            scope: this,
            success: function(xhr) {
                this.setLoading(false);
                var response = Ext.decode(xhr.responseText);
                var samples = response.samples;
                this.items.add(Ext.create('Ext.form.Label', {
                    padding: 10,
                    width: '100%',
                    html: 'Select Posititons for storing new Samples'
                }));
                this.items.add(Ext.create('Ext.panel.Panel', {
                    name: 'containerPositions',
                    layout: 'vbox',
                    flex: 1,
                    style: 'padding-top: 10px;',
                    items: this.getPositionsItems(samples)
                }));
                this.doLayout();
            },
            failure: function() {
                this.setLoading(false);
                this.items.add(Ext.create('Ext.form.Label', {
                    padding: 10,
                    width: '100%',
                    html: '<center style="color: red;">Error occured</center>'
                }));
                this.doLayout();
            }
        });
    },

    getPositionsItems: function(data) {
        var colsItems = [],
            columnsCount = data.columnsCount||6,
            rowsCount = rowsCount||12;
        for (var i=0;i<=columnsCount;i++) {
            var items = [];
            for (var j=0;j<=rowsCount;j++) {
                var name = i+':'+j,
                    conf = data[name], cb;
                if (!conf) {
                    cb = Ext.create('Ext.form.field.Checkbox',  {
                        name: name,
                        hideLabel: true,
                        componentCls: 'slims-wizard-position-cb',
                        fieldStyle: {
                            'margin-top': '6px',
                            'margin-left': '0px'
                        }
                    });
                }
                items.push({
                    xtype: 'container',
                    border: true,
                    items: conf ? [] : cb,
                    layout: {
                        type: 'vbox',
                        align: 'center',
                        pack: 'middle'
                    },
                    height: 30,
                    width: 30,
                    margin: 3,
                    tip: conf ? conf.type.name : '',
                    listeners: {
                        render: function(c) {
                            Ext.create('Ext.tip.ToolTip', {
                                target: c.getEl(),
                                html: c.tip
                            });
                        }
                    },
                    style: {
                        'background-color': conf ? conf.type.color : 'white',
                        'border': conf ? '1px solid darkred;' : '1px solid lightgray;'
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
    }
});