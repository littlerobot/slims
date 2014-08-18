Ext.ns('Ext.slims');

Ext.slims.NewCellLineForm = Ext.extend(Ext.form.FormPanel, {
	constructor: function (config) {
		config = config || {};
		
                var celllinestore = config.cellLineStore;
                
		var that = this;
		config = Ext.apply({
			border: false,
                        url: 'queries/add-cellline.php',
                        errorReader: new Ext.slims.XmlReader(),
                        listeners: {
                            beforeaction: {
                                fn: function (form, action) {
                                    if (action.type === 'submit') {
                                        var testdate = Ext.getCmp('new-cellline-mycoplasma-date').getValue();
                                        if (testdate) {
                                            var testtime = new Date(testdate).getTime() / 1000; // convert to seconds
                                            form.setValues([{
                                                id: 'test-time',
                                                value: testtime
                                            }]);
                                        }
                                    }
                                }
                            }
                        },
			items: [{
                                layout: 'fit',
				border: false,
                                items: [{
					layout: 'border',
					bodyStyle: 'padding: 0px;',
                                        border: false,
					items: [{
						layout: 'form',
						region: 'west',
						frame: false,
						border: true,
						bodyStyle: 'padding: 5px;',
                                                width: 350,
                                                autoScroll: true,
                                                defaults: {
							xtype: 'textfield',
							width: 200,
							mode: 'local',
							msgTarget: 'title'
						},
						items: [{
							id: 'new-cellline-name',
							emptyText: 'Enter new cell line name',
							fieldLabel: 'Cell Line Name',
							allowBlank: false,
							maxLength: 50
						}, {
							id: 'new-cellline-passage-technique',
							emptyText: 'Enter passage technique',
							fieldLabel: 'Passage Technique',
							xtype: 'textarea',
							maxLength: 255
						}, {
                                                    xtype: 'panel',
                                                    layout: 'hbox',
                                                    border: false,
                                                    frame: false,
                                                    fieldLabel: 'Tissue',
                                                    items:[{
                                                            id: 'new-cellline-tissue-combo',
                                                            emptyText: 'Select tissue',
                                                            
                                                            xtype: 'combo',
                                                            mode: 'remote',
                                                            store: new Ext.data.XmlStore({
                                                                    storeId: 'tissue-store',
                                                                    fields: ['id', 'tissue'],
                                                                    record: 'row' , url: 'queries/get-tissue-names.php'
                                                            }),
                                                            displayField: 'tissue',
                                                            valueField: 'id',
                                                            triggerAction: 'all',
                                                            disableKeyFilter: true,
                                                            forceSelection: true,
                                                            editable: false,
                                                            hiddenName: 'new-cellline-tissue' /* this is the actual name which is passed to the server */
                                                        },{
                                                            xtype: 'button',
                                                            text: 'New',
                                                            handler: function() {
                                                                var showTissueWindow = function() {
                                                                    if(!window.tissuewin)
                                                                        window.tissuewin = new Ext.Window({
                                                                            title: 'Add New Tissue',
                                                                            closeAction: 'hide',
                                                                            width: 350,
                                                                            modal: true,
                                                                            resizable: false,
                                                                            buttons: [{
                                                                                text: 'Cancel',
                                                                                handler: function() {
                                                                                    window.tissuewin.hide();
                                                                                }
                                                                            },{
                                                                                text: 'Apply',
                                                                                handler: function() {
                                                                                    var tissue = Ext.getCmp('new-tissue-form-textbox').getValue();
                                                                                    if(!tissue || tissue.length === 0)
                                                                                        return;
                                                                                    var mask = new Ext.LoadMask(window.tissuewin.getEl(), {
                                                                                        msg: 'Saving...'
                                                                                    });
                                                                                            
                                                                                    mask.show();
                                                                                     
                                                                                    Ext.Ajax.request({
                                                                                        url: 'queries/add-tissue.php',
                                                                                        params: { Tissue: tissue},
                                                                                        method: 'post',
                                                                                        callback: function(options, success, response) {
                                                                                            mask.hide();
                                                                                            var reader = new Ext.slims.XmlReader();
                                                                                            var data = reader.read(response);
                                                                                            if(data.success) {
                                                                                                Ext.StoreMgr.get('tissue-store').reload({
                                                                                                    callback: function() {
                                                                                                                                                                                                                      
                                                                                                        Ext.Msg.show({
                                                                                                            title: 'Add Tissue: Success',
                                                                                                            msg: 'Record added successfully',
                                                                                                            icon: Ext.MessageBox.INFO,
                                                                                                            buttons: Ext.Msg.OK,
                                                                                                            fn: function() {
                                                                                                                Ext.getCmp('new-tissue-form-textbox').reset();
                                                                                                            }
                                                                                                        });
                                                                                                        
                                                                                                    }
                                                                                                });
                                                                                            } else {
                                                                                                
                                                                                                Ext.Msg.show({
                                                                                                    title: 'Add Tissue: Fail',
                                                                                                    msg: 'Error adding records. No records added<br/>' + (function() { var s; for(var i = 0, s = ''; i < data.records.length; s += '<br/>' + data.records[i].data.message, i++); return s; })(),
                                                                                                    icon: Ext.MessageBox.ERROR,
                                                                                                    buttons: Ext.Msg.OK
                                                                                                });
                                                                                            }
                                                                                        }
                                                                                    });
                                                                                }
                                                                            }],
                                                                            items: [{
                                                                                layout: 'form',
                                                                                frame: false,
                                                                                border: false,
                                                                                defaults: {
                                                                                    width: 215   
                                                                                },
                                                                                bodyStyle: 'padding: 5px',
                                                                                items: [{
                                                                                    id: 'tissue-form-list',
                                                                                    fieldLabel: 'Tissues',
                                                                                    xtype: 'listview',
                                                                                    autoScroll: true,
                                                                                    height: 300,
                                                                                    store: Ext.StoreMgr.get('tissue-store'),
                                                                                    columns: [{
                                                                                        header: 'Name',
                                                                                        dataIndex: 'tissue'
                                                                                    }]
                                                                                },{
                                                                                    id: 'new-tissue-form-textbox',
                                                                                    xtype: 'textfield',
                                                                                    fieldLabel: 'Tissue Name',
                                                                                    minLength: 1,
                                                                                    maxLength: 255
                                                                                }]
                                                                                
                                                                            }]
                                                                        });
                                                                    var store = Ext.StoreMgr.get('tissue-store');
                                                                    if(store.getCount() === 0) {
                                                                        store.load({
                                                                            callback: function() {
                                                                                window.tissuewin.show();
                                                                            }
                                                                        });
                                                                    } else {
                                                                        window.tissuewin.show();
                                                                    }
                                                                }
                                                                showTissueWindow();
                                                            }
                                                        }
                                                    ]
						}, {
                                                    xtype: 'panel',
                                                    layout: 'hbox',
                                                    border: false,
                                                    frame: false,
                                                    fieldLabel: 'Species',
                                                    items:[{
							id: 'new-cellline-species-combo',
							emptyText: 'Enter species',
							xtype: 'combo',
							mode: 'remote',
							store: new Ext.data.XmlStore({
                                                                storeId: 'species-store',
								fields: ['id', 'species'],
								record: 'row',
								url: 'queries/get-species-names.php'
							}),
							displayField: 'species',
							valueField: 'id',
							triggerAction: 'all',
							disableKeyFilter: true,
							forceSelection: true,
							editable: false,
							hiddenName: 'new-cellline-species' /* this is the actual name which is passed to the server */
                                                    },{
                                                        xtype: 'button',
                                                        text: 'New',
                                                        handler: function() {
                                                                var showSpeciesWindow = function() {
                                                                    if(!window.specwin)
                                                                        window.specwin = new Ext.Window({
                                                                            title: 'Add New Species',
                                                                            closeAction: 'hide',
                                                                            width: 350,
                                                                            modal: true,
                                                                            resizable: false,
                                                                            buttons: [{
                                                                                text: 'Cancel',
                                                                                handler: function() {
                                                                                    window.specwin.hide();
                                                                                }
                                                                            },{
                                                                                text: 'Apply',
                                                                                handler: function() {
                                                                                    var species = Ext.getCmp('new-species-form-textbox').getValue();
                                                                                    if(!species || species.length === 0)
                                                                                        return;
                                                                                    var mask = new Ext.LoadMask(window.specwin.getEl(), {
                                                                                        msg: 'Saving...'
                                                                                    });
                                                                                            
                                                                                    mask.show();
                                                                                     
                                                                                    Ext.Ajax.request({
                                                                                        url: 'queries/add-species.php',
                                                                                        params: { Species: species},
                                                                                        method: 'post',
                                                                                        callback: function(options, success, response) {
                                                                                            mask.hide();
                                                                                            var reader = new Ext.slims.XmlReader();
                                                                                            var data = reader.read(response);
                                                                                            if(data.success) {
                                                                                                Ext.StoreMgr.get('species-store').reload({
                                                                                                    callback: function() {
                                                                                                                                                                                                                      
                                                                                                        Ext.Msg.show({
                                                                                                            title: 'Add Species: Success',
                                                                                                            msg: 'Record added successfully',
                                                                                                            icon: Ext.MessageBox.INFO,
                                                                                                            buttons: Ext.Msg.OK,
                                                                                                            fn: function() {
                                                                                                                Ext.getCmp('new-species-form-textbox').reset();
                                                                                                            }
                                                                                                        });
                                                                                                        
                                                                                                    }
                                                                                                });
                                                                                            } else {
                                                                                                
                                                                                                Ext.Msg.show({
                                                                                                    title: 'Add Species: Fail',
                                                                                                    msg: 'Error adding records. No records added<br/>' + (function() { var s; for(var i = 0, s = ''; i < data.records.length; s += '<br/>' + data.records[i].data.message, i++); return s; })(),
                                                                                                    icon: Ext.MessageBox.ERROR,
                                                                                                    buttons: Ext.Msg.OK
                                                                                                });
                                                                                            }
                                                                                        }
                                                                                    });
                                                                                }
                                                                            }],
                                                                            items: [{
                                                                                layout: 'form',
                                                                                frame: false,
                                                                                border: false,
                                                                                defaults: {
                                                                                    width: 215   
                                                                                },
                                                                                bodyStyle: 'padding: 5px',
                                                                                items: [{
                                                                                    id: 'species-form-list',
                                                                                    fieldLabel: 'Species',
                                                                                    xtype: 'listview',
                                                                                    autoScroll: true,
                                                                                    height: 300,
                                                                                    store: Ext.StoreMgr.get('species-store'),
                                                                                    columns: [{
                                                                                        header: 'Name',
                                                                                        dataIndex: 'species'
                                                                                    }]
                                                                                },{
                                                                                    id: 'new-species-form-textbox',
                                                                                    xtype: 'textfield',
                                                                                    fieldLabel: 'Species Name',
                                                                                    minLength: 1,
                                                                                    maxLength: 255
                                                                                }]
                                                                                
                                                                            }]
                                                                        });
                                                                    var store = Ext.StoreMgr.get('species-store');
                                                                    if(store.getCount() === 0) {
                                                                        store.load({
                                                                            callback: function() {
                                                                                window.specwin.show();
                                                                            }
                                                                        });
                                                                    } else {
                                                                        window.specwin.show();
                                                                    }
                                                                }
                                                                showSpeciesWindow();
                                                            }
                                                    }]
                                                },{
                                                    id: 'new-cellline-growth-mode',
                                                    emptyText: 'Enter growth mode',
                                                    xtype: 'combo',
                                                    store: new Ext.data.ArrayStore({
                                                        storeId: 'growth-mode-store',
                                                        idIndex: 0,
                                                        fields: ['GrowthMode'],
                                                        data: [
                                                            ['Adherent'],
                                                            ['Semi-Adherent'],
                                                            ['Suspension']
                                                        ]
                                                    }),
                                                    fieldLabel: 'Growth Mode',
                                                    valueField: 'GrowthMode',
                                                    displayField: 'GrowthMode',
                                                    /* the display field is sent to the server */
                                                    disableKeyFilter: true,
                                                    forceSelection: true,
                                                    editable: false,
                                                    triggerAction: 'all',
                                                    mode: 'local'
						}, {
							id: 'new-cellline-description',
							emptyText: 'Enter cell line description',
							fieldLabel: 'Description',
							xtype: 'textarea',
							maxLength: 255
						}, {
                                                    xtype: 'panel',
                                                    layout: 'hbox',
                                                    border: false,
                                                    frame: false,
                                                    fieldLabel: 'Morphology',
                                                    items: [{
							id: 'new-cellline-morphology-combo',
							emptyText: 'Select morphology',
							
							xtype: 'combo',
							store: new Ext.data.XmlStore({
                                                                storeId: 'morphology-store',
								fields: ['id', 'morphology'],
								record: 'row',
                                                                url: 'queries/get-morphology-names.php'
							}),
							valueField: 'id',
							displayField: 'morphology',
							disableKeyFilter: true,
							forceSelection: true,
							editable: false,
							triggerAction: 'all',
							mode: 'remote',
							hiddenName: 'new-cellline-morphology' /* this is the actual name which is passed to the server */
                                                    }, {
                                                        xtype: 'button',
                                                        text: 'New',
                                                        handler: function() {
                                                                var showMorphWindow = function() {
                                                                    if(!window.morphwin)
                                                                        window.morphwin = new Ext.Window({
                                                                            title: 'Add New Morphology',
                                                                            closeAction: 'hide',
                                                                            width: 350,
                                                                            modal: true,
                                                                            resizable: false,
                                                                            buttons: [{
                                                                                text: 'Cancel',
                                                                                handler: function() {
                                                                                    window.morphwin.hide();
                                                                                }
                                                                            },{
                                                                                text: 'Apply',
                                                                                handler: function() {
                                                                                    var morphology = Ext.getCmp('new-morphology-form-textbox').getValue();
                                                                                    if(!morphology || morphology.length === 0)
                                                                                        return;
                                                                                    var mask = new Ext.LoadMask(window.morphwin.getEl(), {
                                                                                        msg: 'Saving...'
                                                                                    });
                                                                                            
                                                                                    mask.show();
                                                                                     
                                                                                    Ext.Ajax.request({
                                                                                        url: 'queries/add-morphology.php',
                                                                                        params: { Morphology: morphology},
                                                                                        method: 'post',
                                                                                        callback: function(options, success, response) {
                                                                                            mask.hide();
                                                                                            var reader = new Ext.slims.XmlReader();
                                                                                            var data = reader.read(response);
                                                                                            if(data.success) {
                                                                                                Ext.StoreMgr.get('morphology-store').reload({
                                                                                                    callback: function() {
                                                                                                                                                                                                                      
                                                                                                        Ext.Msg.show({
                                                                                                            title: 'Add Species: Success',
                                                                                                            msg: 'Record added successfully',
                                                                                                            icon: Ext.MessageBox.INFO,
                                                                                                            buttons: Ext.Msg.OK,
                                                                                                            fn: function() {
                                                                                                                Ext.getCmp('new-morphology-form-textbox').reset();
                                                                                                            }
                                                                                                        });
                                                                                                        
                                                                                                    }
                                                                                                });
                                                                                            } else {
                                                                                                
                                                                                                Ext.Msg.show({
                                                                                                    title: 'Add Morphology: Fail',
                                                                                                    msg: 'Error adding records. No records added<br/>' + (function() { var s; for(var i = 0, s = ''; i < data.records.length; s += '<br/>' + data.records[i].data.message, i++); return s; })(),
                                                                                                    icon: Ext.MessageBox.ERROR,
                                                                                                    buttons: Ext.Msg.OK
                                                                                                });
                                                                                            }
                                                                                        }
                                                                                    });
                                                                                }
                                                                            }],
                                                                            items: [{
                                                                                layout: 'form',
                                                                                frame: false,
                                                                                border: false,
                                                                                defaults: {
                                                                                    width: 215   
                                                                                },
                                                                                bodyStyle: 'padding: 5px',
                                                                                items: [{
                                                                                    id: 'morphology-form-list',
                                                                                    fieldLabel: 'Morphology',
                                                                                    xtype: 'listview',
                                                                                    autoScroll: true,
                                                                                    height: 300,
                                                                                    store: Ext.StoreMgr.get('morphology-store'),
                                                                                    columns: [{
                                                                                        header: 'Name',
                                                                                        dataIndex: 'morphology'
                                                                                    }]
                                                                                },{
                                                                                    id: 'new-morphology-form-textbox',
                                                                                    xtype: 'textfield',
                                                                                    fieldLabel: 'Morphology Name',
                                                                                    minLength: 1,
                                                                                    maxLength: 255
                                                                                }]
                                                                                
                                                                            }]
                                                                        });
                                                                    var store = Ext.StoreMgr.get('morphology-store');
                                                                    if(store.getCount() === 0) {
                                                                        store.load({
                                                                            callback: function() {
                                                                                window.morphwin.show();
                                                                            }
                                                                        });
                                                                    } else {
                                                                        window.morphwin.show();
                                                                    }
                                                                }
                                                                showMorphWindow();
                                                            }
                                                    }]
						}, {
							id: 'new-cellline-kayrotype',
							emptyText: 'Enter kayrotype',
							fieldLabel: 'Kayrotype',
							maxLength: 50
						}, {
							id: 'new-cellline-mycoplasma-date',
							emptyText: 'Enter negative mycoplasma test date',
							fieldLabel: 'Negative Mycoplasma Test Date',
							xtype: 'datefield',
							format: 'd/m/Y'
						}, {
							id: 'test-time',
							hidden: true,
							hideLabel: true
						}, {
                                                        xtype: 'panel',
                                                        layout: 'form',
                                                        fileUpload: true,
                                                        fieldLabel: 'Documents',
                                                        height: 250,
                                                        autoWidth: true,
                                                        tbar: [{
                                                            id: 'new-cellline-document-path',
                                                            xtype: 'textfield',
                                                            submitValue: false,
                                                            enableKeyEvents: true,
                                                            width: 155,
                                                            maxLength: 255,
                                                            maxLengthText: 'Maximum length: 255 characters',
                                                            msgTarget: 'title',
                                                            emptyText: 'Enter document path',
                                                            listeners: {
                                                                keyup: {
                                                                    fn: function(field, event) {
                                                                        if(event.keyCode === Ext.EventObject.ENTER) {
                                                                            Ext.getCmp('new-cellline-document-add-button').handler();
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        },{
                                                            id: 'new-cellline-document-add-button',
                                                            text: 'Add',
                                                            handler: function(button, event) {
                                                                var field = Ext.getCmp('new-cellline-document-path');
                                                                var path = field.getValue();
                                                                if(path && path.length < 256) {
                                                                    var docs = Ext.getCmp('new-cellline-documents');
                                                                    var store = docs.getStore();
                                                                    if(!store.getById(path)) {
                                                                        var record = new store.recordType({
                                                                            path: path
                                                                        }, path);
                                                                        store.add([record]);
                                                                        field.setValue('');
                                                                        Ext.getCmp('new-cellline-document-remove-button').setDisabled(store.getCount() === 0);
                                                                    }
                                                                }
                                                            }
                                                        },{
                                                            id: 'new-cellline-document-remove-button',
                                                            text: 'Remove',
                                                            disabled: true,
                                                            handler: function(button, event) {
                                                                var model = Ext.getCmp('new-cellline-documents').getSelectionModel();
                                                                model.each(function(record) {
                                                                    var s = record.store;
                                                                    s.remove(record);
                                                                    button.setDisabled(s.getCount() === 0);
                                                                    return true;
                                                                });
                                                                
                                                            }
                                                        }],
                                                        items: [{
                                                            id: 'new-cellline-documents',
                                                            xtype: 'grid',
                                                            submitValue: false,
                                                            border: false,
                                                            frame: false,
                                                            plain: true,
                                                            autoScroll: true,
                                                            height: 230,
                                                            autoExpandColumn: 'new-cellline-documents-path-column',
                                                            enableHdMenu: false,
                                                            store: new Ext.data.ArrayStore({
                                                                    storeId: 'documents-store',
                                                                    idIndex: 0,
                                                                    idProperty: 'path',
                                                                    fields: ['path'],
                                                                    sortInfo: {field: 'path', direction: 'ASC'}
                                                            }),
                                                            columns: [{
                                                                id: 'new-cellline-documents-path-column',
                                                                header: 'Path',
                                                                dataIndex: 'path',
                                                                sortable: true
                                                            }]
                                                        }]
						}]
					}, {
						id: 'celllinelist',
						xtype: 'grid',
						region: 'center',
						store: celllinestore,
						border: true,
                                                frame: false,
                                                sm: new Ext.grid.RowSelectionModel({
                                                    singleSelect: true
                                                }),
                                                tbar: [{
                                                    text: 'Update Selection',
                                                    handler: function(button, event) {
                                                        var grid = Ext.getCmp('new-cellline-documents');
                                                        var d = [], documents = '';
                                                        grid.getStore().each(function(record) {
                                                            d.push(record.id);    
                                                        });
                                                        if(d.length !== 0)
                                                            documents = d.join('+');
                                                        this.getForm().submit({
                                                            submitEmptyText: false,
                                                            waitMsg: 'Saving...',
                                                            params: { 'new-cellline-documents': documents },
                                                            success: function(form, action) {
                                                                Ext.getCmp('celllinelist').getStore().load({
                                                                    callback: function() {
                                                                        Ext.MessageBox.show({
                                                                            title: 'Add Cell Line',
                                                                            buttons: Ext.MessageBox.OK,
                                                                            icon: Ext.MessageBox.INFO,
                                                                            msg: 'Cell line added successfully'
                                                                        });
                                                                    }
                                                                });
                                                            },
                                                            failure: function(form, action) {
                                                                Ext.MessageBox.show({
                                                                    title: 'Add Cell Line: Fail',
                                                                    buttons: Ext.MessageBox.OK,
                                                                    icon: Ext.MessageBox.ERROR,
                                                                    msg: 'Cell line <b>not</b> added successfully<br/><br/><i>' + action.result.errors[0].message + '</i>'
                                                                });
                                                            }
                                                       });
                                                    },
                                                    scope: this
                                                }, {
                                                    id: 'new-cellline-show-doc-button',
                                                    text: 'Show Document Path(s) for Selected Rows',
                                                    disabled: true,
                                                    handler: function(button, event) {
                                                        var grid = Ext.getCmp('celllinelist');
                                                        var model = grid.getSelectionModel();
                                                        if(model.getCount() === 0) {
                                                            return;
                                                        }
                                                        var row = model.getSelected();
                                                        var cellline = row.get('Name');
                                                        if(cellline) {
                                                            
                                                            Ext.Ajax.request({
                                                                url: 'queries/get-cellline-documents.php?' + Ext.urlEncode({'cellline':cellline}),
                                                                method: 'get',
                                                                callback: function(options, success, response) {
                                                                    var reader = new Ext.data.XmlReader({
                                                                        record: 'row',
                                                                        fields: [ 'CellLineName', 'Document'],
                                                                        mode: 'local',
                                                                        successProperty: '@success'
                                                                    });
                                                                    var data = reader.read(response);
                                                                    if(data.success) {
                                                                        if(data.records.length === 0)
                                                                            return;
                                                                        if(!Ext.slims.documentsWindow) {
                                                                            Ext.slims.documentsWindow = new Ext.Window({
                                                                                modal: true,
                                                                                closeAction: 'hide',
                                                                                layout: 'fit',
                                                                                minWidth: 300,
                                                                                minHeight: 200,
                                                                                width: 300,
                                                                                height: 200,
                                                                                buttons: [{
                                                                                    text: 'OK',
                                                                                    handler: function() {
                                                                                        Ext.slims.documentsWindow.hide();
                                                                                    }
                                                                                }],
                                                                                items: [ new Ext.Panel({
                                                                                    id: 'paths-panel',
                                                                                    xtype: 'panel',
                                                                                    layout: 'form',
                                                                                    bodyStyle: 'padding: 5px',
                                                                                    defaults: { width: 500, editable: false },
                                                                                    labelWidth: 30,
                                                                                    autoScroll: true
                                                                                })]
                                                                            });
                                                                        }
                                                                        var panel = Ext.getCmp('paths-panel');
                                                                        panel.removeAll(true);
                                                                        Ext.each(data.records, function(item, index, all) {
                                                                            var box = new Ext.form.Label({
                                                                                fieldLabel: index + 1
                                                                            });
                                                                            box.setText(item.data.Document);
                                                                            panel.add(box);
                                                                        });
                                                                        Ext.slims.documentsWindow.setTitle('Documents for ' + cellline);
                                                                        Ext.slims.documentsWindow.show();  
                                                                    }
                                                                }
                                                            });
                                                        }
                                                    }
                                                }],
						columns: [{
                                                        id: 'new-cellline-form-celllinelist-name-column',
							header: 'Cell Line Name',
							dataIndex: 'Name'
						}, {
							header: 'Passage Technique',
							dataIndex: 'PassageTechnique'
						}, {
							header: 'Tissue',
							dataIndex: 'TissueName'
						}, {
							header: 'Species',
							dataIndex: 'SpeciesName'
						}, {
							header: 'Growth Mode',
							dataIndex: 'GrowthMode'
						}, {
							header: 'Description',
							dataIndex: 'Description'
						}, {
							header: 'Morphology',
							dataIndex: 'Morphology'
						}, {
							header: 'Kayrotype',
							dataIndex: 'Kayrotype'
						}, {
							header: 'Negative Mycoplasma Test Date',
							dataIndex: 'NegativeMycoplasmaTestDate',
							tpl: '{NegativeMycoplasmaTestDate:date("d/m/Y")}'
						}, {
                                                        header: 'Document(s)',
                                                        dataIndex: 'DocumentCount',
                                                        xtype: 'numbercolumn',
                                                        format: '0',
                                                        align: 'right'
                                                }],
						listeners: {
                                                    render: {
                                                        fn: function (grid) {
                                                            var mask = new Ext.LoadMask(grid.getEl(), {
                                                                msg: 'Loading...'
                                                            });
                                                            mask.show();
                                                            this.getStore().load({
                                                                callback: function() {
                                                                    mask.hide();
                                                                }
                                                            });
                                                            var model = grid.getSelectionModel();
                                                            var button = Ext.getCmp('new-cellline-show-doc-button');
                                                            model.addListener('selectionchange', function() {
                                                                var count = model.getCount();
                                                                if(count === 0) {
                                                                    button.setDisabled(true);
                                                                    return;
                                                                }
                                                                var record = model.getSelected();
                                                                button.setDisabled(record.get('DocumentCount') === 0);
                                                            });
                                                        }
                                                    }
						}

					}]
				}]
			}]
		}, config);
		this.clear = function () {
			var form = Ext.getCmp('cellline-form');
			var items = form.items;
			var clearFunc = function (item, index, length) {
				if (item.items) item.items.each(clearFunc);
				if (item.clearValue) {
					item.clearValue();
					return true;
				}
				if (item.reset) {
					item.reset();
					return true;
				}
				if (item.setValue) {
					item.setValue(null);
					return true;
				}

				return true;
			};
			items.each(clearFunc);
		};
		Ext.slims.NewCellLineForm.superclass.constructor.call(this, config);
	}
});

Ext.reg('new-cellline-form', Ext.slims.NewCellLineForm);