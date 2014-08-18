
Ext.ns('Ext.slims');

Ext.slims.CellSampleEditorGrid = Ext.extend(Ext.grid.EditorGridPanel, {
    constructor: function(config) {
        config = config || {};
        var that = this;
        this.celllineEditor = config.celllineEditor || new Ext.form.ComboBox();
        this.passageNumberEditor = config.passageNumberEditor || new Ext.form.TextField({ maxLength: 20 });
        this.commentEditor = config.commentEditor || new Ext.form.TextArea({ maxLength: 255 });
        this.dateEditor = config.dateEditor || new Ext.form.DateField();
        this.basalMediaEditor = config.basalMediaEditor || new Ext.form.ComboBox();
        this.userEditor = config.userEditor || new Ext.form.ComboBox();
        // hack so we can access it in a nested function
        var bme = this.basalMediaEditor;
        
        var addSelectionHandler = config.addSelectionHandler || Ext.EmptyFn;
        var comboRenderer = function(value, metadata, record, rowindex, columnindex, store) {
            var estore = this.editor.getStore();
            if(!estore)
                return value;
            var evalue = estore.getById(value);
            if(typeof evalue === 'undefined')
                return value;
            return evalue.get(this.editor.displayField);
        }
        
        config = Ext.apply({
            collapsible: false,
            frame: false,
            border: true,
            clicksToEdit: 1,
            store: new Ext.data.ArrayStore({
                idProperty: 'id',
                record: 'row', // this is required so the Writer can use it for each XML record
                fields: [
                        'id',
                        'Dewar',
                        'Stack',
                        'Box',
                        'Position',
                        'Cell-Line',
                        'Passage-Number',
                        'Basal-Media',
                        'Comment',
                        'User',
                        { name: 'Operation-Date', type: 'date', dateFormat: 'd/m/Y' },
                        'Operation-Time',
                        'Operation'
                ],
                autoSave: false,
                sortInfo: {field:'Position', direction:'ASC'},
                writer: new Ext.data.XmlWriter({
                    writeAllFields: true,
                    idProperty: 'id',
                    documentRoot: 'xrequest',
                    root: 'records',
                    forceDocumentRoot: true
                }),
                updatedDataHandler: function() {
                    var button = Ext.getCmp('cell-sample-editor-grid-add-sample-button');
                    button.setDisabled(this.getCount() === 0);
                },
                listeners: {
                    add: {
                        fn: function(store) { store.updatedDataHandler(); }
                    },
                    remove: {
                        fn: function(store) { store.updatedDataHandler(); }
                    },
                    clear: {
                        fn: function(store) { store.updatedDataHandler(); }
                    }
                }
            }),
            listeners: {
                render: {
                    fn: function(grid) {
                        grid.getSelectionModel().lock();
                    }
                }
            },
            tbar: [{
                text: 'Update Selection',
                handler: addSelectionHandler,
                scope: this
            },{
                xtype: 'tbseparator'
            },{
                text: 'Clear Selection',
                handler: function() {
                    that.clear();
                }
            },{
                xtype: 'tbseparator'
            },{
                id: 'cell-sample-editor-grid-add-sample-button',
                text: 'Add Selected Sample(s)',
                disabled: true,
                handler: function() {
                    var editor = Ext.getCmp('sample-editor');
                    var store = editor.getStore();
                    var c = store.getCount();
                    if(c === 0)
                        return false;
                    
                    var params = {};
                    var writer = store.writer;
                    var s = [];
                    for(var i = 0; i < c; i++) {
                        var record = store.getAt(i);
                        var d = record.get('Operation-Date');
                        if(d) {
                            d = new Date(d).getTime() / 1000;
                        }
                        record.set('Operation-Time', d);
                        s.push(record);
                    }
                    writer.apply(params, {}, "create", s);
                    var mask = editor.savemask;
                    if(!mask) {
                        mask = new Ext.LoadMask(editor.getEl(), {
                           msg: 'Saving...'
                       });
                        editor.savemask = mask;
                    }
                    
                    mask.show();                                
                                        
                    Ext.Ajax.request({
                        url: 'queries/add-sample-xml.php',
                        method: 'post',
                        params: params,
                        callback: function(options, success, response) {
                            mask.hide();
                            
                            var reader = new Ext.slims.XmlReader();
                            var data = reader.read(response);
                            if(data.success) {
                                Ext.Msg.show({
                                    title: 'Add Sample: Success',
                                    msg: 'Records added successfully',
                                    icon: Ext.MessageBox.INFO,
                                    buttons: Ext.Msg.OK,
                                    fn: function() { editor.clear(); Ext.getCmp('location-form').refresh(); }
                                });
                                
                            } else {
                                Ext.Msg.show({
                                    title: 'Add Sample: Fail',
                                    msg: 'Error adding records. No records added<br/>' + (function() { var s; for(var i = 0, s = ''; i < data.records.length; s += '<br/>' + data.records[i].data.message, i++); return s; })(),
                                    icon: Ext.MessageBox.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                            }
                            
                        }
                    });
                    return true;
                }
            }],
            colModel: new Ext.grid.ColumnModel({
                defaults: {
                    editable: true,
                    xtype: 'gridcolumn',
                    sortable: true
                },
                columns: [
                    {
                        header: 'Dewar',
                        dataIndex: 'Dewar',
                        editable: false
                    },{
                        header: 'Stack',
                        align: 'right',
                        editable: false,
                        dataIndex: 'Stack'
                    },{
                        header: 'Box',
                        align: 'right',
                        editable: false,
                        dataIndex: 'Box'
                    },{
                        header: 'Position',
                        editable: false,
                        dataIndex: 'Position',
                        renderer: function(value) {
                            if(typeof value !== 'number')
                                return '';
                            value--; // need 0-indexed
                            //return String.fromCharCode(Math.floor(value / 10) + 'A'.charCodeAt(0)) + (value % 10 + 1); // A1 - J10
                            return Ext.slims.coordinate(value);
                        }
                    },{
                        header: 'Cell Line',
                        dataIndex: 'Cell-Line',
                        editor: (function() { if(config.celllineEditor) return new Ext.form.ComboBox(Ext.getCmp(config.celllineEditor).cloneConfig()); return undefined; })()
                    },{
                        header: 'Passage Number',
                        dataIndex: 'Passage-Number',
                        editor: (function() { if(config.passageNumberEditor) return new Ext.form.TextField(Ext.getCmp(config.passageNumberEditor).cloneConfig()); return undefined; })()
                    },{
                        header: 'Basal Media',
                        dataIndex: 'Basal-Media',
                        editor: (function() { if(config.basalMediaEditor) return new Ext.form.ComboBox(Ext.getCmp(config.basalMediaEditor).cloneConfig()); return undefined; })(),
                        renderer: comboRenderer
                    },{
                        header: 'Comment',
                        dataIndex: 'Comment',
                        editor: (function() { if(config.commentEditor) return new Ext.form.TextArea(Ext.getCmp(config.commentEditor).cloneConfig()); return undefined; })()
                    },{
                        header: 'User',
                        dataIndex: 'User',
                        editor: (function() { if(config.operationEditor) return new Ext.form.ComboBox(Ext.getCmp(config.userEditor).cloneConfig()); return undefined; })(),
                        renderer: comboRenderer
                    },{
                        header: 'Date',
                        dataIndex:  'Operation-Date',
                        editor: (function() { if(config.dateEditor) return new Ext.form.DateField(Ext.getCmp(config.dateEditor).cloneConfig()); return undefined; })(),
                        xtype: 'datecolumn',
                        format: 'd/m/Y'
                    },{
                        header: 'Operation',
                        dataIndex: 'Operation',
                        editor: (function() { if(config.operationEditor) return new Ext.form.ComboBox(Ext.getCmp(config.operationEditor).cloneConfig()); return undefined; })(),
                        renderer: comboRenderer
                    }
                ]
            }),
            addRecord: function(object) {
                this.stopEditing();
                var store = this.getStore();
                if(('id' in object) && !store.getById(object.id)) {
                    var record = new store.recordType(object, object.id);
                    //store.insert(store.getCount(), record);
                    store.addSorted(record);
                }
            },
            clear: function() {
                this.getStore().removeAll(false);
            }
        }, config);
        Ext.slims.CellSampleEditorGrid.superclass.constructor.call(this, config);
    }
});

Ext.reg('sample-editor', Ext.slims.CellSampleEditorGrid);