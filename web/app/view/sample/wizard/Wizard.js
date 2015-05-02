Ext.define('Slims.view.sample.wizard.Wizard', {
    extend: 'Ext.window.Window',
    xtype: 'samplewizard',

    layout: 'fit',
    border: false,
    width: 600,
    height: 500,

    title: 'Create Sample Wizard Tool',
    requires: [
        'Ext.form.field.Checkbox',
        'Slims.view.sample.wizard.SampleTypePanel',
        'Slims.view.sample.wizard.PositionsPanel',
        'Slims.view.sample.wizard.SampleInstancePanel'
    ],

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            name: 'cardPanel',
            layout: 'card',
            items: [
                this.getSelectPositionPanel(),
                this.getSelectSampleTypePanel(),
                this.getSelectSampleInstancePanel(),
                this.getSelectContainerPanel()
            ]
        }];

        this.callParent();
    },

    getSelectSampleTypePanel: function() {
        return Ext.create('Slims.view.sample.wizard.SampleTypePanel', {
            buttons: ['->', {
                text: 'Next',
                handler: function() {
                    var valid = this.down('panel[name=cardPanel]').layout.getActiveItem().form.isValid();
                    if (valid) {
                        this.nextTab();
                    }
                },
                scope: this
            }]
        });
    },

    getSelectSampleInstancePanel: function() {
        return Ext.create('Slims.view.sample.wizard.SampleInstancePanel', {
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Next',
                handler: function() {
                    var valid = this.down('panel[name=cardPanel]').layout.getActiveItem().form.isValid();
                    if (valid) {
                        this.nextTab();
                    }
                },
                scope: this
            }]
        });
    },

    getSelectContainerPanel: function() {
        return Ext.create('Ext.form.Panel', {
            layout: 'fit',
            name: 'step3',
            tbar: [{
                xtype: 'label',
                padding: 10,
                html: 'Select Container for storing new Sample and press <i>Next</i>'
            }],
            items: [{
                xtype: 'containersgrid',
                readOnly: true,
                listeners: {
                    selectionchange: function(tree, selected) {
                        tree.view.up('panel[name=step3]').down('button[name=next]').setDisabled(!selected.length);
                    }
                }
            }],
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Next',
                name: 'next',
                disabled: true,
                handler: this.nextTab,
                scope: this
            }]
        });
    },

    getSelectPositionPanel: function() {
        return Ext.create('Slims.view.sample.wizard.PositionsPanel', {
            data: this.getTempData().samples,
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Finish',
                handler: this.commitChanges,
                scope: this
            }]
        });
    },

    nextTab: function() {
        var cardPanel = this.down('panel[name=cardPanel]');
        cardPanel.layout.setActiveItem(cardPanel.layout.getNext());
    },

    prevTab: function() {
        var cardPanel = this.down('panel[name=cardPanel]');
        cardPanel.layout.setActiveItem(cardPanel.layout.getPrev());
    },

    commitChanges: function() {},

    getTempData: function() {
        return {
            "success": true,
            "samples": {
                "1:1": {
                    "type": {
                        "sample_type_template": 1,
                        "id": 1,
                        "name": "Test sample type",
                        "attributes": [
                            {
                                "sample_type_template": 1,
                                "label": "Sex",
                                "id": 1,
                                "value": "Trypsin"
                            },
                            {
                                "sample_type_template": 2,
                                "label": "Age",
                                "id": 2,
                                "value": "Typice, tam asinos esset mortuis, sicut stupri frixam gallinaceo, sed tu accidere ad detrahendum hoc cacas Dum ego in a transeuntem, tempus sic non wanna te occidere, volo te adjuvet. Sed non hoc tibi, quod mihi non sunt. Praeterea, Ive 'iam fuerunt nimio cacas hoc mane super hoc casu eam tradere vestris mutum asinum."
                            },
                            {
                                "sample_type_template": 3,
                                "label": "Comment",
                                "id": 3,
                                "filename": "spreadsheet.xls",
                                "mime_type": "application/vnd.ms-excel",
                                "url": "/app_dev.php/download/3/spreadsheet.xls"
                            },
                            {
                                "sample_type_template": 4,
                                "label": "Date",
                                "id": 4,
                                "value": "2015-02-02"
                            }
                        ]
                    },
                    "template": {
                        "id": 8,
                        "name": "Template name 3",
                        "store": [
                            {
                                "id": 4,
                                "order": 1,
                                "label": "Store Sex",
                                "type": "option",
                                "options": [
                                    "Male",
                                    "Female"
                                ],
                                "activity": "store"
                            },
                            {
                                "id": 5,
                                "order": 2,
                                "label": "Store Age",
                                "type": "brief-text",
                                "activity": "store"
                            },
                            {
                                "id": 6,
                                "order": 3,
                                "label": "Store Comment",
                                "type": "long-text",
                                "activity": "store"
                            }
                        ],
                        "remove": [
                            {
                                "id": 7,
                                "order": 1,
                                "label": "Remove Sex",
                                "type": "option",
                                "options": [
                                    "Male",
                                    "Female"
                                ],
                                "activity": "remove"
                            },
                            {
                                "id": 8,
                                "order": 2,
                                "label": "Remove Age",
                                "type": "brief-text",
                                "activity": "remove"
                            },
                            {
                                "id": 9,
                                "order": 3,
                                "label": "Remove Comment",
                                "type": "long-text",
                                "activity": "remove"
                            }
                        ]
                    },
                    "attributes": [
                        {
                            "id": 1,
                            "value": "Male"
                        }
                    ]
                },
                "1:2": {
                    "type": {
                        "sample_type_template": 1,
                        "id": 4,
                        "name": "Mouse embryos",
                        "attributes": [
                            {
                                "sample_type_template": 1,
                                "label": "Sex",
                                "id": 9,
                                "value": "Trypsin"
                            },
                            {
                                "sample_type_template": 2,
                                "label": "Age",
                                "id": 10,
                                "value": "Typice, tam asinos esset mortuis, sicut stupri frixam gallinaceo, sed tu accidere ad detrahendum hoc cacas Dum ego in a transeuntem, tempus sic non wanna te occidere, volo te adjuvet. Sed non hoc tibi, quod mihi non sunt. Praeterea, Ive 'iam fuerunt nimio cacas hoc mane super hoc casu eam tradere vestris mutum asinum."
                            },
                            {
                                "sample_type_template": 3,
                                "label": "Comment",
                                "id": 11,
                                "filename": "spreadsheet.xls",
                                "mime_type": "application/vnd.ms-excel",
                                "url": "/app_dev.php/download/11/spreadsheet.xls"
                            },
                            {
                                "sample_type_template": 4,
                                "label": "Date",
                                "id": 12,
                                "value": "2015-02-01"
                            }
                        ]
                    },
                    "template": {
                        "id": 8,
                        "name": "Template name 3",
                        "store": [
                            {
                                "id": 4,
                                "order": 1,
                                "label": "Store Sex",
                                "type": "option",
                                "options": [
                                    "Male",
                                    "Female"
                                ],
                                "activity": "store"
                            },
                            {
                                "id": 5,
                                "order": 2,
                                "label": "Store Age",
                                "type": "brief-text",
                                "activity": "store"
                            },
                            {
                                "id": 6,
                                "order": 3,
                                "label": "Store Comment",
                                "type": "long-text",
                                "activity": "store"
                            }
                        ],
                        "remove": [
                            {
                                "id": 7,
                                "order": 1,
                                "label": "Remove Sex",
                                "type": "option",
                                "options": [
                                    "Male",
                                    "Female"
                                ],
                                "activity": "remove"
                            },
                            {
                                "id": 8,
                                "order": 2,
                                "label": "Remove Age",
                                "type": "brief-text",
                                "activity": "remove"
                            },
                            {
                                "id": 9,
                                "order": 3,
                                "label": "Remove Comment",
                                "type": "long-text",
                                "activity": "remove"
                            }
                        ]
                    },
                    "attributes": []
                },
                "1:3": {
                    "type": {
                        "sample_type_template": 1,
                        "id": 4,
                        "name": "Mouse embryos",
                        "attributes": [
                            {
                                "sample_type_template": 1,
                                "label": "Sex",
                                "id": 9,
                                "value": "Trypsin"
                            },
                            {
                                "sample_type_template": 2,
                                "label": "Age",
                                "id": 10,
                                "value": "Typice, tam asinos esset mortuis, sicut stupri frixam gallinaceo, sed tu accidere ad detrahendum hoc cacas Dum ego in a transeuntem, tempus sic non wanna te occidere, volo te adjuvet. Sed non hoc tibi, quod mihi non sunt. Praeterea, Ive 'iam fuerunt nimio cacas hoc mane super hoc casu eam tradere vestris mutum asinum."
                            },
                            {
                                "sample_type_template": 3,
                                "label": "Comment",
                                "id": 11,
                                "filename": "spreadsheet.xls",
                                "mime_type": "application/vnd.ms-excel",
                                "url": "/app_dev.php/download/11/spreadsheet.xls"
                            },
                            {
                                "sample_type_template": 4,
                                "label": "Date",
                                "id": 12,
                                "value": "2015-02-01"
                            }
                        ]
                    },
                    "template": {
                        "id": 8,
                        "name": "Template name 3",
                        "store": [
                            {
                                "id": 4,
                                "order": 1,
                                "label": "Store Sex",
                                "type": "option",
                                "options": [
                                    "Male",
                                    "Female"
                                ],
                                "activity": "store"
                            },
                            {
                                "id": 5,
                                "order": 2,
                                "label": "Store Age",
                                "type": "brief-text",
                                "activity": "store"
                            },
                            {
                                "id": 6,
                                "order": 3,
                                "label": "Store Comment",
                                "type": "long-text",
                                "activity": "store"
                            }
                        ],
                        "remove": [
                            {
                                "id": 7,
                                "order": 1,
                                "label": "Remove Sex",
                                "type": "option",
                                "options": [
                                    "Male",
                                    "Female"
                                ],
                                "activity": "remove"
                            },
                            {
                                "id": 8,
                                "order": 2,
                                "label": "Remove Age",
                                "type": "brief-text",
                                "activity": "remove"
                            },
                            {
                                "id": 9,
                                "order": 3,
                                "label": "Remove Comment",
                                "type": "long-text",
                                "activity": "remove"
                            }
                        ]
                    },
                    "attributes": []
                },
                "1:4": {
                    "type": {
                        "sample_type_template": 1,
                        "id": 5,
                        "name": "Mouse embryos2",
                        "attributes": [
                            {
                                "sample_type_template": 1,
                                "label": "Sex",
                                "id": 13,
                                "value": "Trypsin"
                            },
                            {
                                "sample_type_template": 2,
                                "label": "Age",
                                "id": 14,
                                "value": "Typice, tam asinos esset mortuis, sicut stupri frixam gallinaceo, sed tu accidere ad detrahendum hoc cacas Dum ego in a transeuntem, tempus sic non wanna te occidere, volo te adjuvet. Sed non hoc tibi, quod mihi non sunt. Praeterea, Ive 'iam fuerunt nimio cacas hoc mane super hoc casu eam tradere vestris mutum asinum."
                            },
                            {
                                "sample_type_template": 3,
                                "label": "Comment",
                                "id": 15,
                                "filename": "spreadsheet.xls",
                                "mime_type": "application/vnd.ms-excel",
                                "url": "/app_dev.php/download/15/spreadsheet.xls"
                            },
                            {
                                "sample_type_template": 4,
                                "label": "Date",
                                "id": 16,
                                "value": "2015-02-01"
                            }
                        ]
                    },
                    "template": {
                        "id": 7,
                        "name": "Template name",
                        "store": [
                            {
                                "id": 1,
                                "order": 1,
                                "label": "Store Sex",
                                "type": "option",
                                "options": [
                                    "Male",
                                    "Female"
                                ],
                                "activity": "store"
                            },
                            {
                                "id": 2,
                                "order": 2,
                                "label": "Store Age",
                                "type": "brief-text",
                                "activity": "store"
                            },
                            {
                                "id": 3,
                                "order": 3,
                                "label": "Store Comment",
                                "type": "long-text",
                                "activity": "store"
                            }
                        ],
                        "remove": []
                    },
                    "attributes": [
                        {
                            "id": 4,
                            "value": "Male"
                        }
                    ]
                }
            }
        };
    }
});