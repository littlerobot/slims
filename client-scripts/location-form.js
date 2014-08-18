
Ext.ns('Ext.slims');

Ext.slims.LocationForm = Ext.extend(Ext.FormPanel, {
                constructor: function(config) {
                    config = config || {};
                    
                    var that = this;
                    var items = config.items || [];
                    delete config.items;
                                      
                                
                    var dewarstore = new Ext.data.XmlStore({
                        storeId: 'dewar-store',
                        url: 'queries/get-dewar-info.php',
                        params: {
                            grouponly: (typeof config.grouponly === "undefined") ? 0 : config.grouponly
                        }, 
                        record: 'row',
                        fields: [ 'Dewar']
                    });
                     
                                
                    var stackstore = new Ext.data.XmlStore({
                        storeId: 'stack-store',
                        url: 'queries/get-dewar-info.php',
                        record: 'row',
                        fields: ['Stack']
                    });
                                
                    var boxstore = new Ext.data.XmlStore({
                        storeId: 'box-store',
                        url: 'queries/get-dewar-info.php',
                        record: 'row',
                        fields: ['Box']
                    });
                               
                    var positionstore = new Ext.data.XmlStore({
                        storeId: 'position-store',
                        url: 'queries/get-dewar-info.php',
                        record: 'row',
                        fields: ['PositionID', 'Position', 'Sample', 'Height', 'Width'],
                        resetData: function() {
                            this.removeAll();
                            var records = [];
                            for(var i = 0; i < 100; i++) {
                                var record = new this.recordType({
                                    Position: Ext.slims.coordinate(i),
                                    PositionID: i + 1,
                                    Sample: -1,
                                    Height: 10,
                                    Width: 10
                                }, i);
                                records.push(record);
                            }
                            this.add(records);
                        }
                    });
                    positionstore.resetData();
                    
                    var tpl = new Ext.XTemplate(
                        '<tpl for=".">',
                            '<tpl if="PositionID % Height == 1"><div style="float:left;width:20px;"></tpl>',
                                '<div class="thumb-wrap" id="{Position}">',
                                    '<tpl if="!this.hasNoValue(Sample)">',
                                            '<div class="thumb"><img src="images/vial-small.png" title="{Position}"></div>',
                                    '</tpl>', 
                                    '<tpl if="this.hasNoValue(Sample)">',
                                        '<div class="thumb-empty"><div title="{Position} (Empty)"></div></div>',
                                    '</tpl>',
                                '</div>',
                            '<tpl if="PositionID % Height == 0"></div></tpl>',
                        '</tpl>',
                        '<div class="x-clear"></div>',
                        {
                            // XTemplate configuration:
                            compiled: true,
                            disableFormats: true,
                            // member functions:
                            hasNoValue: function(value){
                                return value == -1 || value == "";
                            }
                        }
                    );
                    
                    config = Ext.apply({
                        id: config.id,
                        labelWidth: 100, // label settings here cascade unless overridden
                        frame: false,
                        bodyStyle:'padding:5px 5px 0',
                        border: false,
                        width: 350,
                        defaults: {width: 215, mode: 'local', allowBlank: false, msgTarget: 'title' },
                        defaultType: 'textfield',
                        layout: 'form',   
                        items: [
                            
                            
                                {
                                    xtype: 'combo',
                                    id: config.id + '-location-dewar',
                                    fieldLabel: 'Dewar',
                                    triggerAction: 'all',
                                    emptyText: 'Select a dewar',
                                    valueField: 'Dewar',
                                    displayField: 'Dewar',
                                    disableKeyFilter: true,
                                    forceSelection: true,
                                    editable: false,
                                    store: dewarstore,
                                    mode: 'remote',
                                    listeners: {
                                        select: {
                                            fn: function(combo, record, index) {
                                                var stack = Ext.getCmp(config.id + '-location-stack');
                                                var box = Ext.getCmp(config.id + '-location-box');
                                                var position = Ext.getCmp(config.id + '-location-position');
                                                position.clearSelections();
                                                stack.clearValue();
                                                box.clearValue();
                                                
                                                var ss = stack.getStore();
                                                ss.load({
                                                    params: {
                                                        Dewar: combo.getValue()
                                                    },
                                                    callback: function(records, options, success) {
                                                        stack.setDisabled(ss.getCount() == 0);
                                                        box.setDisabled(true);
                                                        position.disable();
                                                    }
                                                });
                                                
                                            }
                                        }
                                    }
                                },
                                
                                {
                                    xtype: 'combo',
                                    id: config.id + '-location-stack',
                                    fieldLabel: 'Stack',
                                    triggerAction: 'all',
                                    valueField: 'Stack',
                                    displayField: 'Stack',
                                    emptyText: 'Select a stack',
                                    disableKeyFilter: true,
                                    forceSelection: true,
                                    editable: false,
                                    mode: 'local',
                                    store: stackstore,
                                    listeners: {
                                        added: {
                                            fn: function(combo) {
                                                combo.setDisabled(true);
                                            }
                                        },
                                        select: {
                                            fn: function(combo, record, index) {
                                                var dewar = Ext.getCmp(config.id + '-location-dewar');
                                                var box = Ext.getCmp(config.id + '-location-box');
                                                var position = Ext.getCmp(config.id + '-location-position');
                                                position.clearSelections();
                                                box.clearValue();
                                                var bs = box.getStore();
                                                bs.load({
                                                    params: {
                                                        Dewar: dewar.getValue(),
                                                        Stack: combo.getValue()
                                                    },
                                                    callback: function(records, options, success) {
                                                        box.setDisabled(bs.getCount() == 0);
                                                        position.disable();
                                                    }
                                                });
                                                
                                            }
                                        }
                                    }
                                },
                                
                                {
                                    xtype: 'combo',
                                    id: config.id + '-location-box',
                                    fieldLabel: 'Box',
                                    triggerAction: 'all',
                                    valueField: 'Box',
                                    displayField: 'Box',
                                    emptyText: 'Select a box',
                                    disableKeyFilter: true,
                                    forceSelection: true,
                                    editable: false,
                                    store: boxstore,
                                    mode: 'local',
                                    listeners: {
                                        added: {
                                            fn: function(combo) {
                                                combo.setDisabled(true);
                                            }
                                        },
                                        select: {
                                            fn: function(combo, record, index) {
                                                var dewar = Ext.getCmp(config.id + '-location-dewar');
                                                var stack = Ext.getCmp(config.id + '-location-stack');
                                                var position = Ext.getCmp(config.id + '-location-position');
                                                position.clearSelections();
                                                var ps = position.getStore();
                                                ps.load({
                                                    params: {
                                                        Dewar: dewar.getValue(),
                                                        Stack: stack.getValue(),
                                                        Box: combo.getValue()
                                                    },
                                                    callback: function(records, options, success) {
                                                        position.enable();
                                                    }
                                                });
                                                //Ext.getCmp(config.id + '-location-images-view').resetPositionData(p);
                                                
                                                //position.setDisabled(position.store.getCount() == 0);
                                            }
                                        }
                                    }
                                },
                                             
                                {
                                    xtype: 'panel',
                                    id: config.id + '-location-images-view',
                                    frame: false,
                                    plain: true,
                                    border: true,
                                    style: 'background: #ebf3fd;',
                                    bodyStyle:'padding:5px 5px 0; background: transparent;',
                                    collapsible: false,
                                    layout:'fit',
                                    fieldLabel: 'Position',
                                    cls: 'location-images-view',
                                    title: '0 positions selected',
                                    buttons: [{
                                        id: config.id + '-location-position-clear',
                                        xtype: 'button',
                                        text: 'Clear Selection',
                                        listeners: {
                                            click: {
                                                fn: function() {
                                                    var position = Ext.getCmp(config.id + '-location-position');
                                                    position.clearSelections();
                                                }
                                            }
                                        }
                                    },{
                                        id: config.id + '-location-position-refresh',
                                        xtype: 'button',
                                        text: 'Refresh',
                                        listeners: {
                                            click: {
                                                fn: function() {
                                                    that.refresh();
                                                }
                                            }
                                        }
                                    }],
                                    items: [
                                        new Ext.DataView({
                                            id: config.id + '-location-position',
                                            store: positionstore,
                                            tpl: tpl,
                                            autoHeight: true,
                                            multiSelect: true,
                                            overClass:'x-view-over',
                                            itemSelector:'div.thumb-wrap',
                                            emptyText: 'No positions to display',
                                            selectType: config.selectType || 'all',
                                            plugins: [
                                                new Ext.DataView.DragSelector()
                                            ],
                                            listeners: {
                                                beforeselect: {
                                                    fn: function(view, node, selections) {
                                                        if(view.disabled)
                                                            return false;
                                                        if(view.selectType === 'all')
                                                            return true;
                                                        var s = (Ext.query("div[@class = 'thumb-empty']", node).length != 0);
                                                        return view.selectType === 'empty' ? s : !s;
                                                    }
                                                },
                                                selectionchange: {
                                                    fn: function(view, nodes) {
                                                        var n = nodes.length;
                                                        var plural = (n != 1);
                                                        Ext.getCmp(config.id + '-location-images-view').setTitle(n + ' position' + (plural?'s':'') + ' selected');
                                                    }
                                                },
                                                added: {
                                                    fn: function(view) {
                                                        view.disable();
                                                        var imagesview = Ext.getCmp(config.id + '-location-images-view');
                                                        imagesview.setTitle('0 positions selected');
                                                        imagesview.resetPositionData();
                                                    }
                                                }
                                            }
                                        })
                                    ],
                                    resetPositionData: function(data) {
                                        if(!data || data.length == 0) {
                                            data = [];
                                            for(var i = 0; i < 100; i++) {
                                                var r = [];
                                                r.push(i + 1); // 1- 100
                                                r.push(String.fromCharCode(Math.floor(i / 10) + 'A'.charCodeAt(0)) + (i % 10 + 1)); // A1 - J10
                                                r.push(-1); // empty
                                                data.push(r);
                                            }
                                        }
                                        var position = Ext.getCmp(config.id + '-location-position');
                                        // position.getStore().loadData(data);
                                    }
                                
                        }].concat(items),
                        listeners: {
                            enable: {
                                fn: function(panel) {
                                    var dewar = Ext.getCmp(config.id + '-location-dewar');
                                    var stack = Ext.getCmp(config.id + '-location-stack');
                                    var box = Ext.getCmp(config.id + '-location-box');
                                    var position = Ext.getCmp(config.id + '-location-position');
                                    if(!dewar.getValue() || dewar.getValue().length === 0) {
                                        stack.setDisabled(true);
                                        box.setDisabled(true);
                                        position.setDisabled(true);
                                        return;
                                    }
                                    if(!stack.getValue() || stack.getValue().length === 0) {
                                        box.setDisabled(true);
                                        position.setDisabled(true);
                                        return;
                                    }
                                    if(!box.getValue() || box.getValue().length === 0) {
                                        position.setDisabled(true);
                                        return;
                                    }
                                }
                            }
                        }
                    }, config);
                    this.clear = function() {
                        var position = Ext.getCmp(config.id + '-location-position');
                        position.clearSelections();
                        var view = Ext.getCmp(config.id + '-location-images-view');
                        view.resetPositionData();
                        position.disable();
                        var dewar = Ext.getCmp(config.id + '-location-dewar');
                        dewar.clearValue();
                        var stack = Ext.getCmp(config.id + '-location-stack');
                        stack.clearValue();
                        stack.disable();
                        var box = Ext.getCmp(config.id + '-location-box');
                        box.clearValue();
                        box.disable();
                    };
                    this.getPositions = function() {
                        var position = Ext.getCmp(config.id + '-location-position');
                        var indices = position.getSelectedIndexes();
                        for(var i = 0, length = indices.length; i < length; i++) {
                            indices[i]++;
                        }
                        return indices;
                    };
                    this.getSelectedPositionIndices = function() {
                        var position = Ext.getCmp(config.id + '-location-position');
                        return position.getSelectedIndexes();
                    },
                    this.getDewar = function() {
                        var dewar = Ext.getCmp(config.id + '-location-dewar');
                        return dewar.getValue();
                    };
                    this.getStack = function() {
                        var stack = Ext.getCmp(config.id + '-location-stack');
                        return stack.getValue();
                    };
                    this.getBox = function() {
                        var box = Ext.getCmp(config.id + '-location-box');
                        return box.getValue();
                    };
                    this.refresh = function() {
                        /* refresh the grid after samples have been added */
                        
                        var dewar = Ext.getCmp(config.id + '-location-dewar');
                        var stack = Ext.getCmp(config.id + '-location-stack');
                        var box = Ext.getCmp(config.id + '-location-box');
                        var position = Ext.getCmp(config.id + '-location-position');
                        if(position.disabled)
                            return;
                        /* reload the main store */
                        position.getStore().reload({
                            callback: function() {
                                /* once loaded, fire the box's select event, causing position's data to be refreshed */
                                var box = Ext.getCmp(config.id + '-location-box');
                                box.fireEvent('select', box);
                                position.refresh(); // refresh the grid with the new data
                            }
                        });
                        
                    };
                     
                    Ext.slims.LocationForm.superclass.constructor.call(this, config);
                }
});

Ext.reg('locationform', Ext.slims.LocationForm);