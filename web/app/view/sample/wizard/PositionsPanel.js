Ext.define('Slims.view.sample.wizard.PositionsPanel', {
    extend: 'Ext.panel.Panel',
    xtype: 'positionsview',

    layout: {
        align: 'center',
        type: 'vbox'
    },
    container: null,
    items: [],

    initComponent: function() {
        this.on('show', this.getContainerPositions, this);

        this.callParent();
    },

    getContainerPositions: function() {
        this.setLoading('Loading...');

        Ext.Ajax.request({
            url: Slims.Url.getRoute('getcontainerpositions', [this.container.get('id')]),
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
                this.items.add(Ext.create('Ext.form.Label', {
                    padding: 10,
                    width: '100%',
                    html: '<center style="color: red;">Error occured.</center>'
                }));
                this.doLayout();
            }
        });
    },

    setSamplesData: function(samples) {
        this.items.removeAll();
        this.items.add(Ext.create('Ext.form.Label', {
            padding: 10,
            width: '100%',
            html: 'Select Posititons for storing new Samples'
        }));

        this.items.add(Ext.create('Ext.form.Panel', {
            name: 'containerPositions',
            layout: 'vbox',
            flex: 1,
            style: 'padding-top: 10px;',
            items: this.getPositionsItems(samples)
        }));

        this.doLayout();
    },

    getPositionsItems: function(data) {
        var colsItems = [],
            columnsCount = this.container.get('columns'),
            rowsCount = this.container.get('rows');

        for (var i=0;i<=columnsCount;i++) {
            var items = [];

            for (var j=0;j<=rowsCount;j++) {
                var name = i+':'+j,
                    conf = data[name], cb, tipHtml = null;

                if (conf) {
                    tipHtml = [
                        '<div><b>Sample storing here:</b></div>',
                        '<b><i>Name:</i></b> ' + conf.type.name,
                        '<b><i>Sample Template:</i></b> ' + conf.type.sample_type_template_name,
                        '<b><i>Template Name:</i></b> ' + conf.template.name
                    ].join('</br>');
                } else {
                    cb = Ext.create('Ext.form.field.Checkbox',  {
                        name: name,
                        inputValue: true,
                        hideLabel: true,
                        tooltip: name,
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
                    items: conf ? [] : [cb],
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
                        'background-color': conf ? conf.color : 'white',
                        'border': conf ? '1px solid darkred' : '1px solid lightgray'
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