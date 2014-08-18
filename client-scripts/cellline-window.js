
Ext.ns('Ext.slims');

Ext.slims.NewCellLineWindow = Ext.extend(Ext.Window, {
constructor: function(config) {
    config = config || {};
    if(!('store' in config))
        throw Error('Cell Line Window requires Ext.data.Store');
    var celllinestore = config['store'];
    var that = this;
    config = Ext.apply({
        closeAction: 'hide',
        modal: true,
        width: 660, minWidth: 660, maxWidth: 660,
        height: 340, minHeight: 340,
        layout: 'accordion',
        layoutConfig: {
            autoWidth: false
        },
        resizable: false,
        title: "New Cell Line",
        buttons: [ {
            
             text: 'Clear Form',
             listeners: {
                 click: {
                     fn: function() {
                         that.clear();
                     }
                 }
             }
         },{
            text: 'Cancel',
            listeners: {
                click: {
                    fn: function() {
                        that.hide();
                        that.clear();
                    }
                }
            }
        },{
            text: 'Apply',
            listeners: {
                click: {
                    fn: function() {
                        var panel = Ext.getCmp('cellline-form');
                        panel.getForm().submit({
                            submitEmptyText: false,
                            waitMsg: 'Saving...',
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
                    }
                }
            }
        }],  
        items: [{
            id: 'cellline-form',
            xtype: 'form',
            hideBorders: true,
            url: 'queries/add-cellline.php',
            frame: true,
            plain: true,
            errorReader: new Ext.slims.XmlReader(),
            title: 'Details',
            listeners: {
                beforeaction: {
                    fn: function(form, action) {
                        if(action.type === 'submit') {
                            var testdate = Ext.getCmp('new-cellline-mycoplasma-date').getValue();
                            if(testdate) {
                                var testtime = new Date(testdate).getTime() / 1000; // convert to seconds
                                form.setValues([{id: 'test-time', value: testtime }]);
                            }
                        }
                    }
                }
            }, 
            items: [{
                //xtype: 'form',
                layout: 'column',
                items: [{
                        layout: 'form',
                        //width: 310,
                        columnWidth: .50,
                        bodyStyle:'padding:0',
                        defaults: { xtype: 'textfield', width: 200, mode: 'local', msgTarget: 'title' },
                        items: [{
                            id: 'new-cellline-name',
                            emptyText: 'Enter new cell line name',
                            fieldLabel: 'Cell Line Name',
                            allowBlank: false,
                            maxLength: 50
                        },{
                            id: 'new-cellline-passage-technique',
                            emptyText: 'Enter passage technique',
                            fieldLabel: 'Passage Technique',
                            xtype: 'textarea',
                            maxLength: 255
                        },{
                            id: 'new-cellline-tissue-combo',
                            emptyText: 'Select tissue',
                            fieldLabel: 'Tissue',
                            xtype: 'combo',
                            mode: 'remote',
                            store: new Ext.data.XmlStore({
                                fields: ['id', 'tissue'],
                                record: 'row',
                                url: 'queries/get-tissue-names.php?pc=' + new Date().getTime()
                            }),
                            displayField: 'tissue',
                            valueField: 'id',
                            triggerAction: 'all',
                            disableKeyFilter: true,
                            forceSelection: true,
                            editable: false,
                            hiddenName: 'new-cellline-tissue' /* this is the actual name which is passed to the server */
                        },{
                            id: 'new-cellline-species-combo',
                            emptyText: 'Enter species',
                            fieldLabel: 'Species',
                            xtype: 'combo',
                            mode: 'remote',
                            store: new Ext.data.XmlStore({
                                fields: ['id', 'species'],
                                id: 'id',
                                record: 'row',
                                url: 'queries/get-species-names.php?pc=' + new Date().getTime(),
                                expandData: true
                            }),
                            displayField: 'species',
                            valueField: 'id',
                            triggerAction: 'all',
                            disableKeyFilter: true,
                            forceSelection: true,
                            editable: false,
                            hiddenName: 'new-cellline-species' /* this is the actual name which is passed to the server */
                        },{
                            id: 'new-cellline-growth-mode',
                            emptyText: 'Enter growth mode',
                            xtype: 'combo',
                            store: new Ext.data.ArrayStore({
                                idIndex: 0,
                                fields: ['GrowthMode'],
                                data: [['Adherent'],['Semi-Adherent'],['Suspension']]
                            }),
                            fieldLabel: 'Growth Mode',
                            valueField: 'GrowthMode',
                            displayField: 'GrowthMode', /* the display field is sent to the server */
                            disableKeyFilter: true,
                            forceSelection: true,
                            editable: false,
                            triggerAction: 'all'
                        }]
                   
                },{
                    layout: 'form',
                    columnWidth: .5,
                    bodyStyle:'padding:0',
                    defaults: { xtype: 'textfield', width: 200, mode: 'local' },
                    items: [{
                            id: 'new-cellline-description',
                            emptyText: 'Enter cell line description',
                            fieldLabel: 'Description',
                            xtype: 'textarea',
                            maxLength: 255
                        },{
                            id: 'new-cellline-morphology-combo',
                            emptyText: 'Select morphology',
                            fieldLabel: 'Morphology',
                            xtype: 'combo',
                            store: new Ext.data.XmlStore({
                                fields: ['id', 'morphology'],
                                id: 'id',
                                record: 'row',
                                url: 'queries/get-morphology-names.php?pc=' + new Date().getTime()
                            }),
                            valueField: 'id',
                            displayField: 'morphology',
                            disableKeyFilter: true,
                            forceSelection: true,
                            editable: false,
                            triggerAction: 'all',
                            mode: 'remote',
                            hiddenName: 'new-cellline-morphology' /* this is the actual name which is passed to the server */
                        },{
                            id: 'new-cellline-kayrotype',
                            emptyText: 'Enter kayrotype',
                            fieldLabel: 'Kayrotype',
                            maxLength: 50
                        },{
                            id: 'new-cellline-mycoplasma-date',
                            emptyText: 'Enter negative mycoplasma test date',
                            fieldLabel: 'Negative Mycoplasma Test Date',
                            xtype: 'datefield',
                            format: 'd/m/Y'
                        }, {
                            id: 'test-time',
                            hidden: true,
                            hideLabel: true
                        },
                        new Ext.ux.form.FileUploadField({
                            id: 'new-cellline-documents',
                            fieldLabel: 'Documents',
                            emptyText: 'Enter a document path',
                            maxLength: 255,
                            submitValue: false // we don't actually want to submit this value, just get the path and add it to the document list
                        })
                    ] /* end of second column items */
                    
                }]
            }]
        },{
                    id: 'celllinelist',
                    xtype: 'grid',
                    autoScroll: false,
                    store: celllinestore,
                    height: 200,
                    hideBorders: true,
                    title: 'Current Cell Lines',
                    viewConfig: {
                        forceFit: false
                    },
                    columns: [{
                        header: 'Cell Line Name',
                        dataIndex: 'Name'
                    },{
                        header: 'Passage Technique',
                        dataIndex: 'PassageTechnique'
                    },{
                        header: 'Tissue',
                        dataIndex: 'TissueName'
                    },{
                        header: 'Species',
                        dataIndex: 'SpeciesName'
                    },{
                        header: 'Growth Mode',
                        dataIndex: 'GrowthMode'
                    },{
                        header: 'Description',
                        dataIndex: 'Description'
                    },{
                        header: 'Morphology',
                        dataIndex: 'Morphology'
                    },{
                        header: 'Kayrotype',
                        dataIndex: 'Kayrotype'
                    },{
                        header: 'Negative Mycoplasma Test Date',
                        dataIndex: 'NegativeMycoplasmaTestDate',
                        tpl: '{NegativeMycoplasmaTestDate:date("d/m/Y")}'
                    }]
                
            },{
                layout: 'form',
                title: 'Documents',
                items: [{
                    xtype: 'grid',
                    store: new Ext.data.ArrayStore({
                        idIndex: 0,
                        fields: ['id', 'path'],
                        data: [
                            [1, 'C:\\path\\to\\file'],
                            [2, '/path/to/another/file']
                        ]
                    }),
                    autoExpandColumn: 1,
                    columns: [
                        {
                            header:'id',
                            dataIndex: 'id',
                            align: 'right' //, width: .1
                        },
                        {
                            header: 'Path',
                            dataIndex: 'path'
                        }
                    ]
                }]
            }]
         
    
    }, config);
    this.clear = function() {
        var form = Ext.getCmp('cellline-form');
        var items = form.items;
        var clearFunc = function(item, index, length) {
            if(item.items)
                item.items.each(clearFunc);
            if(item.clearValue) {
                item.clearValue();
                return true;
            }
            if(item.reset) {
                item.reset();
                return true;
            }
            if(item.setValue) {
                item.setValue(null);
                return true;
            }
            
            return true;
        };
        items.each(clearFunc);
    };
    Ext.slims.NewCellLineWindow.superclass.constructor.call(this, config);
}
});