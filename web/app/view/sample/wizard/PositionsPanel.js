Ext.define('Slims.view.sample.wizard.PositionsPanel', {
    extend: 'Ext.panel.Panel',
    xtype: 'positionsview',

    layout: {
        type: 'vbox',
        align: 'center'
    },
    container: null,

    initComponent: function() {
        this.on('show', this.buildItems, this);
        this.callParent(arguments);
    },

    buildItems: function() {
        this.setLoading('Loading...');
        this.removeAll();

        // DEBUG: test code
        this.setLoading(false);
        return this.setSamplesData(this.getTempData().samples);

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
                    html: '<center style="color: red;">Error occured</center>'
                }));
                this.doLayout();
            }
        });
    },

    setSamplesData: function(samples) {
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

    getPositionsItems: function(data) {
        var colsItems = [],
            columnsCount = this.container.get('columns'),
            rowsCount = this.container.get('rows');
        for (var i=0;i<=columnsCount;i++) {
            var items = [];
            for (var j=0;j<=rowsCount;j++) {
                var name = i+':'+j,
                    conf = data[name], cb, tipHtml = 'Check it to save your sample here!';
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
                } else {
                    tipHtml = [
                        '<div><b>Sample storing here:</b></div>',
                        '<b><i>Name:</i></b> ' + conf.type.name,
                        '<b><i>Sample Template:</i></b> ' + conf.type.sample_type_template_name,
                        '<b><i>Template Name:</i></b> ' + conf.template.name
                    ].join('</br>');
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
                    tip: tipHtml,
                    listeners: {
                        render: function(c) {
                            Ext.create('Ext.tip.ToolTip', {
                                target: c.getEl(),
                                html: c.tip
                            });
                        }
                    },
                    style: {
                        'background-color': conf ? conf.color : 'white',
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
    },

    getTempData: function() {
        return {
            "success": true,
            "samples": {
                "1:1": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 1,
                        "name": "Test sample type"
                    },
                    "template": {
                        "id": 8,
                        "name": "Template name 3"
                    },
                    "attributes": [
                        {
                            "id": 1,
                            "value": "Male"
                        }
                    ],
                    color: 'blue'
                },
                "1:2": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 4,
                        "name": "Mouse embryos"
                    },
                    "template": {
                        "id": 8,
                        "name": "Template name 3"
                    },
                    "attributes": [],
                    color: 'red'
                },
                "1:3": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 4,
                        "name": "Mouse embryos"
                    },
                    "template": {
                        "id": 8,
                        "name": "Template name 3"
                    },
                    "attributes": [],
                    color: 'yellow'
                },
                "6:8": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 5,
                        "name": "Mouse embryos2"
                    },
                    "template": {
                        "id": 7,
                        "name": "Template name"
                    },
                    "attributes": [
                        {
                            "id": 4,
                            "value": "Male"
                        }
                    ]
                },
                "2:8": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 5,
                        "name": "Mouse embryos2"
                    },
                    "template": {
                        "id": 7,
                        "name": "Template name"
                    },
                    "attributes": [
                        {
                            "id": 4,
                            "value": "Male"
                        }
                    ]
                },
                "2:12": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 5,
                        "name": "Mouse embryos2"
                    },
                    "template": {
                        "id": 7,
                        "name": "Template name"
                    },
                    "attributes": [
                        {
                            "id": 4,
                            "value": "Male"
                        }
                    ]
                },
                "2:10": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 5,
                        "name": "Mouse embryos2"
                    },
                    "template": {
                        "id": 7,
                        "name": "Template name"
                    },
                    "attributes": [
                        {
                            "id": 4,
                            "value": "Male"
                        }
                    ],
                    color: 'blue'
                },
                "1:8": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 5,
                        "name": "Mouse embryos2"
                    },
                    "template": {
                        "id": 7,
                        "name": "Template name"
                    },
                    "attributes": [
                        {
                            "id": 4,
                            "value": "Male"
                        }
                    ],
                    color: 'blue'
                },
                "4:4": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 5,
                        "name": "Mouse embryos2"
                    },
                    "template": {
                        "id": 7,
                        "name": "Template name"
                    },
                    "attributes": [
                        {
                            "id": 4,
                            "value": "Male"
                        }
                    ],
                    color: 'green'
                },
                "4:5": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 5,
                        "name": "Mouse embryos2"
                    },
                    "template": {
                        "id": 7,
                        "name": "Template name"
                    },
                    "attributes": [
                        {
                            "id": 4,
                            "value": "Male"
                        }
                    ],
                    color: 'green'
                },
                "4:6": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 5,
                        "name": "Mouse embryos2"
                    },
                    "template": {
                        "id": 7,
                        "name": "Template name"
                    },
                    "attributes": [
                        {
                            "id": 4,
                            "value": "Male"
                        }
                    ],
                    color: 'green'
                },
                "4:7": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 5,
                        "name": "Mouse embryos2"
                    },
                    "template": {
                        "id": 7,
                        "name": "Template name"
                    },
                    "attributes": [
                        {
                            "id": 4,
                            "value": "Male"
                        }
                    ],
                    color: 'green'
                },
                "4:8": {
                    "type": {
                        "sample_type_template": 1,
                        "sample_type_template_name": "Template 1",
                        "id": 5,
                        "name": "Mouse embryos2"
                    },
                    "template": {
                        "id": 7,
                        "name": "Template name"
                    },
                    "attributes": [
                        {
                            "id": 4,
                            "value": "Male"
                        }
                    ],
                    color: 'green'
                }
            }
        };
    }
});