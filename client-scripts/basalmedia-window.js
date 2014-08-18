Ext.ns('Ext.slims');

Ext.slims.NewBasalMediaWindow = Ext.extend(Ext.Window, {
    constructor: function(config) {
        config = config || {};
        if(!('store' in config))
            throw Error('NewBasalMediaWindow requires Ext.data.Store');
        var basalmediastore = config['store'];
        var that = this;
        var textfield = new Ext.form.TextField({
            id: 'basalmedia',
            fieldLabel: 'New Media Name',
            emptyText: 'Enter new basal media name',
            allowBlank: false,
            maxLength: 50,
            msgTarget: 'title'
        });
        config = Ext.apply({
                    closeAction: 'hide',
                    modal: true,
                    title: 'New Basal Media',
                    width: 330,
                    resizable: false,
                    buttons: [{
                                //xtype: 'button',
                                text: 'Cancel',
                                listeners: {
                                    click: {
                                        fn: function() {
                                            that.hide();
                                        }
                                    }
                                }
                            },{
                                //xtype: 'button',
                                text: 'Apply',
                                listeners: {
                                    click: {
                                        fn: function() {
                                            
                                            var panel = Ext.getCmp('basal-media-form');
                                            panel.getForm().submit({
                                                waitMsg: 'Saving...',
                                                success: function(form, action) {
                                                    panel.getComponent('basalmedia-listview').getStore().load({
                                                        callback: function() {
                                                            Ext.MessageBox.show({
                                                                title: 'Add Basal Media',
                                                                buttons: Ext.MessageBox.OK,
                                                                icon: Ext.MessageBox.INFO,
                                                                msg: 'Media added successfully'
                                                            });
                                                        }
                                                    });
                                                   
                                                },
                                                failure: function(form, action) {
                                                    Ext.MessageBox.show({
                                                        title: 'Add Basal Media: Fail',
                                                        buttons: Ext.MessageBox.OK,
                                                        icon: Ext.MessageBox.ERROR,
                                                        msg: 'Media <b>not</b> added successfully<br/><br/><i>' + action.result.errors[0].message + '</i>'
                                                    });
                                                }
                                            });
                                        }
                                    }
                                }
                        }],
                    items: [{
                        id: 'basal-media-form',
                        xtype: 'form',
                        layout: 'form',
                        url: 'queries/add-basal-media.php',
                        frame: true,
                        labelWidth: 100,
                        bodyStyle:'padding:5px 5px 0',
                        width: 320,
                        defaults: {width: 185},
                        errorReader: new Ext.data.XmlReader({
                                record: 'result',
                                success: '@success'
                            },
                            [ 'id', 'message']
                        ),
                        listeners: {
                            beforeaction: {
                                fn: function(form, action) {
                                    if(action.type === 'submit') {
                                       
                                    }
                                }
                            }
                        },
                        
                        items: [{
                            id: 'basalmedia-listview',
                            xtype: 'grid',
                            fieldLabel: 'Current Media',
                            store: basalmediastore,
                            height: 200,
                            columns: [{
                                header: 'Basal Media Name',
                                dataIndex: 'Name'
                            }]
                        }, textfield]
                    }]
                }, config);
        
                this.clear = function() {
                    textfield.reset();
                };
                Ext.slims.NewBasalMediaWindow.superclass.constructor.call(this, config);
                
    }
});