<?php
    session_start();
    
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    $_SESSION['REMOTE_USER'] = 'sal61';
?><!DOCTYPE html>

<html>
    <head>
        <title>SLIMS</title>
        <link rel="stylesheet" href="http://cdn.sencha.io/ext-4.1.0-gpl/resources/css/ext-all.css"/>
        <link rel="stylesheet" href="http://cdn.sencha.io/ext-4.1.0-gpl/examples/view/data-view.css"/>
        <style type="text/css">
            .thumb, .thumb-empty {
                height: 70px;
                font-family: tahoma;
                font-size: 11px;
                padding: 5px;
            }
            .thumb-empty {
                background-color: #e9eCe8;
            }
            .thumb-column {
                float: left;
                margin: 5px;
                width: 100px;
            }
            .thumb-wrap {
                border: 1px solid #99BCE8;
                margin-bottom:10px;
            }
            .x-view-over {
                border-width: 2px;
                margin-left: -1px;
                margin-top: -1px;
                margin-bottom: 9px;
            }
            .x-view-selected {
                border-width: 3px;
                margin-left: -2px;
                margin-top: -2px;
                margin-bottom: 8px;
            }
        </style>
        <script type="text/javascript" src="http://cdn.sencha.io/ext-4.1.0-gpl/ext-all-debug.js"></script>
        <script type="text/javascript" src="http://cdn.sencha.io/ext-4.1.0-gpl/examples/ux/DataView/DragSelector.js"></script>
        <script type="text/javascript">
        
            Ext.Loader.setConfig({enabled: true});

            
            
            Ext.require([
                'Ext.data.*',
                'Ext.util.*',
                'Ext.view.View',
                'Ext.ux.DataView.DragSelector'
            ]);
        
            Ext.onReady(function() {
                
                Ext.define('Sample', {
                    extend: 'Ext.data.Model',
                    fields: [{
                        name: 'location',
                        type: 'integer'
                    }, {
                        name: 'cellline',
                        type: 'string'
                    }]
                });
                
                Ext.define('DewarModel', {
                    extend: 'Ext.data.Model',
                    fields: [{
                        name: 'Dewar',
                        type: 'string'
                    }]
                });
                
                Ext.define('StackModel', {
                    extend: 'Ext.data.Model',
                    fields: [{
                        name: 'Dewar',
                        type: 'string'
                    },
                    {
                        name: 'Stack',
                        type: 'string'
                    }]
                });
                
                Ext.create('Ext.data.Store', {
                    id:'sample-store',
                    model: 'Sample',
                    data: [
                       { location: 1, cellline: "" },
{ location: 2, cellline: "Pete's cell line" },
{ location: 3, cellline: "Pete's cell line" },
{ location: 4, cellline: "" },
{ location: 5, cellline: "Pete's cell line" },
{ location: 6, cellline: "" },
{ location: 7, cellline: "Pete's cell line" },
{ location: 8, cellline: "Pete's cell line" },
{ location: 9, cellline: "" },
{ location: 10, cellline: "Pete's cell line" },
{ location: 11, cellline: "" },
{ location: 12, cellline: "Pete's cell line" },
{ location: 13, cellline: "" },
{ location: 14, cellline: "" },
{ location: 15, cellline: "" },
{ location: 16, cellline: "" },
{ location: 17, cellline: "Pete's cell line" },
{ location: 18, cellline: "" },
{ location: 19, cellline: "Pete's cell line" },
{ location: 20, cellline: "Pete's cell line" },
{ location: 21, cellline: "Pete's cell line" },
{ location: 22, cellline: "Pete's cell line" },
{ location: 23, cellline: "" },
{ location: 24, cellline: "Pete's cell line" },
{ location: 25, cellline: "" },
{ location: 26, cellline: "Pete's cell line" },
{ location: 27, cellline: "Pete's cell line" },
{ location: 28, cellline: "Pete's cell line" },
{ location: 29, cellline: "" },
{ location: 30, cellline: "Pete's cell line" },
{ location: 31, cellline: "" },
{ location: 32, cellline: "Pete's cell line" },
{ location: 33, cellline: "Pete's cell line" },
{ location: 34, cellline: "" },
{ location: 35, cellline: "" },
{ location: 36, cellline: "" },
{ location: 37, cellline: "" },
{ location: 38, cellline: "Pete's cell line" },
{ location: 39, cellline: "" },
{ location: 40, cellline: "" },
{ location: 41, cellline: "" },
{ location: 42, cellline: "Pete's cell line" },
{ location: 43, cellline: "" },
{ location: 44, cellline: "Pete's cell line" },
{ location: 45, cellline: "" },
{ location: 46, cellline: "Pete's cell line" },
{ location: 47, cellline: "Pete's cell line" },
{ location: 48, cellline: "" },
{ location: 49, cellline: "" },
{ location: 50, cellline: "" },
{ location: 51, cellline: "Pete's cell line" },
{ location: 52, cellline: "Pete's cell line" },
{ location: 53, cellline: "" },
{ location: 54, cellline: "" },
{ location: 55, cellline: "" },
{ location: 56, cellline: "" },
{ location: 57, cellline: "Pete's cell line" },
{ location: 58, cellline: "Pete's cell line" },
{ location: 59, cellline: "" },
{ location: 60, cellline: "" },
{ location: 61, cellline: "" },
{ location: 62, cellline: "Pete's cell line" },
{ location: 63, cellline: "Pete's cell line" },
{ location: 64, cellline: "Pete's cell line" },
{ location: 65, cellline: "Pete's cell line" },
{ location: 66, cellline: "Pete's cell line" },
{ location: 67, cellline: "" },
{ location: 68, cellline: "" },
{ location: 69, cellline: "" },
{ location: 70, cellline: "Pete's cell line" },
{ location: 71, cellline: "" },
{ location: 72, cellline: "" },
{ location: 73, cellline: "Pete's cell line" },
{ location: 74, cellline: "" },
{ location: 75, cellline: "" },
{ location: 76, cellline: "" },
{ location: 77, cellline: "Pete's cell line" },
{ location: 78, cellline: "Pete's cell line" },
{ location: 79, cellline: "" },
{ location: 80, cellline: "" },
{ location: 81, cellline: "Pete's cell line" },
{ location: 82, cellline: "Pete's cell line" },
{ location: 83, cellline: "Pete's cell line" },
{ location: 84, cellline: "Pete's cell line" },
{ location: 85, cellline: "Pete's cell line" },
{ location: 86, cellline: "Pete's cell line" },
{ location: 87, cellline: "" },
{ location: 88, cellline: "Pete's cell line" },
{ location: 89, cellline: "Pete's cell line" },
{ location: 90, cellline: "Pete's cell line" },
{ location: 91, cellline: "" },
{ location: 92, cellline: "Pete's cell line" },
{ location: 93, cellline: "" },
{ location: 94, cellline: "" },
{ location: 95, cellline: "Pete's cell line" },
{ location: 96, cellline: "" },
{ location: 97, cellline: "Pete's cell line" },
{ location: 98, cellline: "Pete's cell line" },
{ location: 99, cellline: "Pete's cell line" },
{ location: 100, cellline: "" }
                    ]
                });
                
                var template = new Ext.XTemplate(
                                        '<tpl for=".">',
                                            '<tpl if="location % 10 == 1"><div class="thumb-column"></tpl>',
                                                '<div class="thumb-wrap"  id="{location}">',
                                                    '<tpl if="cellline.length !== 0">',
                                                            '<div class="thumb x-unselectable">{cellline}</div>',
                                                    '</tpl>', 
                                                    '<tpl if="cellline.length === 0">',
                                                        '<div class="thumb-empty x-unselectable"><div title="{location} (Empty)">&nbsp;</div></div>',
                                                    '</tpl>',
                                                '</div>',
                                            '<tpl if="location % 10 == 0"></div></tpl>',
                                        '</tpl>',
                                        '<div class="x-clear"></div>'
                                    );
                
                var view = Ext.create('Ext.view.View', {
                   
                    store: Ext.data.StoreManager.lookup('sample-store'),
                    tpl: template,
                    itemSelector: 'div.thumb-wrap',
                    overItemCls:'x-view-over',
                    selectedItemCls: 'x-view-selected',
                    trackOver: true,
                    multiSelect: true,
                    plugins: [
                        Ext.create('Ext.ux.DataView.DragSelector', {})
                    ],
                    listeners: {
                        beforeselect: function(view, record, options) {
                            if(view.disabled)
                                return false;
                            var s = (record.get("cellline").length !== 0);
                            return !s;
                        },
                        selectionchange: function(dv, nodes ){
                            var l = nodes.length,
                                s = l !== 1 ? 's' : '';
                            this.up('panel').setTitle('Cell Samples (' + l + ' sample' + s + ' selected)');
                        }
                    }
                });
                
                var panel = Ext.create("Ext.container.Viewport", {
                    renderTo: Ext.getBody(),
                    mode: 'local',
                    layout: 'border',
                    items: [{
                        xtype: 'panel',
                        layout: 'form',
                        region: 'west',
                        width: 300,
                        title: "west",
                        bodyStyle: 'padding: 5px',
                        defaults: {
                            labelWidth: 100
                        },
                        items: [{
                            id: 'dewar-combo',
                            xtype: 'combobox',
                            fieldLabel: 'Dewar',
                            store: Ext.create('Ext.data.Store', {
                                storeId: 'dewar-store',
                                
                                model: 'DewarModel',
                                autoLoad: false,
                                proxy: {
                                    // load using HTTP
                                    type: 'ajax',
                                    url: 'queries/get-dewar-info.php',
                                    // the return will be XML, so lets set up a reader
                                    reader: {
                                        type: 'xml',
                                        // records will have an "Item" tag
                                        record: 'row',
                                        //idProperty: 'Dewar',
                                        totalRecords: '@total'
                                    }
                                }
                                , listeners : {
                                    load: function() {
                                        var i = 0;
                                    }
                                }
                            }),
                            displayField: 'Dewar',
                            valueField: 'Dewar'
                        },{
                            xtype: 'combobox',
                            fieldLabel: 'Stack',
                            store: Ext.create('Ext.data.Store', {
                                storeId: 'stack-store',
                                model: 'StackModel',
                                autoLoad: false,
                                proxy: {
                                    // load using HTTP
                                    type: 'ajax',
                                    url: 'queries/get-dewar-info.php',
                                    // the return will be XML, so lets set up a reader
                                    reader: {
                                        type: 'xml',
                                        // records will have an "Item" tag
                                        record: 'row',
                                        
                                        //idProperty: 'Dewar',
                                        totalRecords: '@total'
                                    }
                                }
                                , listeners : {
                                    added: function() {
                                        var stack = this;
                                        var dewar = Ext.getCmp('dewar-combo');
                                        dewar.on('select', function(combo, records, options) {
                                            stack.getStore().filter({
                                                property: 'Dewar',
                                                value: dewar.getValue()
                                            });
                                        });
                                    }
                                }
                            }),
                            displayField: 'Stack',
                            valueField: 'Stack'
                        },{
                            xtype: 'combobox',
                            fieldLabel: 'Box'
                        }]
                    }, {
                        xtype: 'panel',
                        title: 'Samples',
                        region: 'center',
                        items: view
                    }]
                });
                
                // Ext.StoreMgr.get('dewar-store').load();
            });
        </script>
    </head>
    <body>
        <div id="target"/>
    </body>
</html>
