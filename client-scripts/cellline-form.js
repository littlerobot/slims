
Ext.ns('Ext.slims');

Ext.slims.CellLineForm = Ext.extend(Ext.form.FormPanel, {
    constructor: function(config) {
        config = config || {};
        
        var that = this;
        
        this.user = config.user ? config.user : 'Sally';
        
        var newCellLineButtonHandler = config.newCellLineButtonHandler || Ext.emptyFn;
        
        this.setNewCellLineButtonHandler = function(handler) {
            var button = that.getComponent('cellline-subpanel').getComponent('cellline-button');
            if(button)
                button.on('click', handler);
        }
               
        var celllinestore = config.cellLineStore;
        
        /* new Ext.data.XmlStore({
            id: 'Name',    
            url: 'queries/get-celllines.php',
            record: 'row',
            fields: ['Name','PassageTechnique','TissueName','SpeciesName','Description','Morphology', 'GrowthMode', 'Kayrotype', {name: 'NegativeMycoplasmaTestDate', type: 'date'}]
        }); */
                   
        var basalmediastore = new Ext.data.XmlStore({
            storeId: 'basal-media-store',    
            url: 'queries/get-basal-media-names.php',
            idProperty: 'ID',
            record: 'row',
            fields: ['ID', 'Name']
        });
        
        this.getCellLineStore = function() {
            return celllinestore;        
        };
        
        this.getBasalMediaStore = function() {
            return basalmediastore;  
        };
        
        this.getCellLineEditor = function() {
            return Ext.getCmp('celllinecombo');
        };
        
        config = Ext.apply({
                // id: 'cellline-panel',
                border: false,
                //title: 'Cell Line Details',
                width: 350,
                defaults: {
                    width: 215,
                    mode: 'local',
                    labelWidth: 100,
                    msgTarget: 'title'
                },
                bodyStyle: 'padding:5px',
                defaultType: 'textfield',
                layout: 'form',
                errorReader: new Ext.slims.XmlReader(),
                items: [
                {
                    id: 'cellline-subpanel',
                    xtype: 'panel',
                    border: false,
                    frame: false,
                    labelWidth: 100,
                    fieldLabel: 'Cell Line',
                    layout: 'hbox',
                    items: [{
                        id: 'cellname',
                        fieldLabel: 'Name',
                        xtype: 'combo',
                        emptyText: 'Select an existing cell line',
                        triggerAction: 'all',
                        width: 180,
                        store: celllinestore,
                        valueField: 'Name',
                        displayField: 'Name',
                        disableKeyFilter: true,
                        forceSelection: true,
                        editable: false,
                        allowBlank: false
                    },{
                        id:'cellline-button',
                        text: 'New',
                        xtype: 'button',
                        width: 35,
                        listeners: {
                            click: {
                                fn: newCellLineButtonHandler
                            }
                        }
                    }]
                    
                },{
                    id: 'passage-number',
                    fieldLabel: 'Passage Number',
                    emptyText: 'Enter the sample\'s passage number',
                    maxLength: 20
                },{
                    id: 'basalmedia-panel',
                    xtype: 'panel',
                    border: false,
                    frame: false,
                    labelWidth: 100,
                    fieldLabel: 'Basal Media',
                    layout: 'hbox',
                    items: [{
                        id: 'basal-media-combo',
                        hiddenName: 'basal-media',
                        xtype: 'combo',
                        width: 180,
                        emptyText: 'Select an existing basal media',
                        store: basalmediastore,
                        triggerAction: 'all',
                        valueField: 'ID',
                        displayField: 'Name',
                        disableKeyFilter: true,
                        forceSelection: true,
                        editable: false
                    }, {
                        id:'basalmedia-button',
                        text: 'New',
                        xtype: 'button',
                        width: 35,
                        listeners: {
                            click: {
                                fn: function() {
                                    basalmediastore.load({
                                        callback: function() {
                                            if(typeof that.basalmediawindow === 'undefined') {
                                                that.basalmediawindow = new Ext.slims.NewBasalMediaWindow({ store: basalmediastore }); 
                                            }
                                            that.basalmediawindow.clear();
                                            that.basalmediawindow.show();
                                        }
                                    });
                                }
                            }
                        }
                    }]
                },{
                    id: 'comment',
                    xtype: 'textarea',
                    fieldLabel: 'Comment',
                    emptyText: 'Enter a comment',
                    maxLength: 255
                }, {
                    id: 'operation-user-combo',
                    xtype: 'combo',
                    fieldLabel: 'User',
                    emptyText: 'Enter a User\'s name',
                    maxLength: 50,
                    triggerAction: 'all',
                    mode: 'remote',
                    disablekeyFilter: false,
                    store: {
                        xtype: 'xmlstore',
                        storeId: 'sample-operation-user-store',
                        idProperty: 'Staff',
                        url: 'queries/get-activity-users-xml.php',
                        fields: ['Staff', 'ResearchGroup'],
                        record: 'row'
                    },
                    displayField: 'Staff',
                    tpl: new Ext.XTemplate('<tpl for=".">',
                                                '<div class="x-combo-list-item" ext:qtip="{Staff} - {ResearchGroup}">',
                                                    '<span>',
                                                        '{Staff}',
                                                    '</span>',
                                                    '&nbsp;',
                                                    '<span style="color:gray">',
                                                        '{ResearchGroup}',
                                                    '</span>',
                                                '</div>',
                                            '</tpl>')
                }, {
                    id: 'operation-date',
                    xtype: 'datefield',
                    fieldLabel: 'Date',
                    value: new Date(),
                    format: 'd/m/Y',
                    emptyText: 'Enter date sample added'
                },{
                    id: 'operation-time',
                    hidden: true,
                    hideLabel: true
                },{
                    id: 'operation',
                    xtype: 'combo',
                    fieldLabel: 'Operation',
                    valueField: 'id',
                    displayField: 'operation',
                    disabled: false,
                    editable: false,
                    submitValue: true,
                    value: 'Freeze',
                    store: new Ext.data.ArrayStore({
                        idIndex: 0,
                        fields: ['id','operation'],
                        data: [[1,'Freeze']]
                    })
                }],
                listeners: {
                    beforeaction: {
                        fn: function(form, action) {
                            if(action.type === 'submit') {
                               var date = Ext.getCmp('operation-date').getValue();
                                if(date) {
                                    var time = new Date(date).getTime() / 1000; // convert to seconds
                                    form.setValues([{id: 'operation-time', value: time }]);
                                }
                            }
                        }
                    }
                }
            },
            
        config);
        
        /* clear all form field values */
        this.clear = function() {
            var panel = this; // Ext.getCmp('cellline-panel');
            var clearFunc = function(item, index, length) {
                // recurse into nested items
                if(item.items)
                    item.items.each(clearFunc);
                // handle exemptions               
                if(item.disabled || item.id === 'operation')
                    return true;
                // handle interface inonsistencies
                if(item.clearValue) {
                    item.clearValue();
                    return true;
                }
                if(item.setValue) {
                    item.setValue(null);
                    return true;
                }                                    
                return true;
            };
            panel.items.each(clearFunc);
        };
        
        var getter = function(fieldname, componentid, valuefunc) {
            if(!that[fieldname]) {
                that[fieldname] = Ext.getCmp(componentid);
            }
            var field = that[fieldname];
            valuefunc = valuefunc || 'getValue';
            if(typeof valuefunc === 'function') {
                return valuefunc.call(field);
            }
            return that[fieldname][valuefunc]();
        }
        
        /* getters */
        this.getCellLineName = function() {
            return getter('celllinecombo', 'cellname');
        }
        
        this.getPassageNumber = function() {
            return getter('passagenumberfield', 'passage-number');
        }
        
        this.getBasalMedia = function() {
            return getter('basalmediacombo', 'basal-media-combo', function() {
                var index = this.selectedIndex;
                if(index === undefined || index === null || index < 0)
                    return '';
                var record = this.getStore().getAt(index);
                return record.get("Name");
            });
        }
        
        this.getOperation = function() {
            return getter('operationcombo', 'operation');
        }
        
        this.getOperationUser = function() {
            return getter('operationusercombo', 'operation-user-combo');
        }
        
        this.getOperationDate = function() {
            return getter('operationdatefield', 'operation-date');
        }
        
        this.getComment = function() {
            return getter('comment','comment');
        }
        Ext.slims.CellLineForm.superclass.constructor.call(this, config);
    }
});

Ext.reg('celllineform', Ext.slims.CellLineForm);