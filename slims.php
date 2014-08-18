<?php
    session_start();
    include_once("queries/connect-database-read.php");
    include_once("session.php");
    
    /*
        slims.php
        
        this is the main (and only) screen.
        the GUI is written using the ExtJS3 javascript library
        
        The GUI is a single tabpanel with the following Tabs:
        
        - Contents - users can peruse and search the database. All users have access to this.
        - Add Sample - SLIMS users can add samples to boxes. Non-admin SLIMS users can add sample to their own stacks, admin user can add sample to all stacks
        - New Cell Line - users can add new cell lines that can be referenced when adding new samples. SLIMS users only.
        - Remove Sample - Remove existing sample. Same rules as Add Sample.
        - Users - manage SLIMS users. Admin users only.
        - Stacks - manage stack owners. Admin users only.
        - History - view Add/Remove history. Admin users only
    */
      
    $isvalid = isValidUser();
    $id = getUserName();
    $isadmin = isAdminUser();
    
    // echo print_r($_SESSION);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
    <title>SLIMS</title>
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <!-- Load Ext JS Library -->
    <link rel="stylesheet" type="text/css" href="http://extjs.cachefly.net/ext-3.2.1/resources/css/ext-all.css">
    <link type="text/css" rel="stylesheet" href="ext-3.2.1/ux/css/data-view.css">
    <link rel="stylesheet" type="text/css" href="ext-3.2.1/ux/css/RowEditor.css">

    <style type="text/css">
        a.slims-tabpanel-link {
            position: absolute;
            right: 5px;
            top: 5px;
            font-family: tahoma,arial,verdana,sans-serif;
            font-size: 11px;
            font-weight: bold;
        }
        
        span#tabpanel-name {
            position: absolute;
            right: 50px;
            top: 5px;
            font-family: tahoma,arial,verdana,sans-serif;
            font-size: 11px;
        }
        
        #loading-mask{
            position:absolute;
            left:0;
            top:0;
            width:100%;
            height:100%;
            z-index:20000;
            background-color:white;
        }
        #loading{
            position:absolute;
            left:45%;
            top:40%;
            padding:2px;
            z-index:20001;
            height:auto;
        }
        #loading img {
            margin-bottom:5px;
            display: inline-block;
        }
        
        .loading-indicator {
            font-size:11px;
            background-image:url(images/loading.gif);
            background-repeat: no-repeat;
            background-position:top left;
            padding-left:20px;
            height:18px;
            text-align:left;
            font-size: 11px;
        }
         
        .not-displayed {
            display: none;
        }
        
        #slimslogo {
            margin: 8px 5px;
        }
        
        #header {
            /* float: left; */
            margin: 8px 5px;
            margin-left: 50px;
            font-size: 18px;
            color: #455C6F;
            font-variant: small-caps;
        }
    </style>
    
    <script type="text/javascript" src="http://extjs.cachefly.net/ext-3.2.1/adapter/ext/ext-base-debug.js"> </script>
    <script type="text/javascript" src="http://extjs.cachefly.net/ext-3.2.1/ext-all-debug.js"> </script>
    
    <script src="client-scripts/xml-reader.js" type="text/javascript"></script>
    
    <script src="ext-3.2.1/ux/DataView-more.js" type="text/javascript"></script>
    <script src="client-scripts/location-form.js" type="text/javascript"></script>
    <script src="client-scripts/basalmedia-window.js" type="text/javascript"></script>
    <script src="client-scripts/cellline-form.js" type="text/javascript"></script>
    <script src="client-scripts/cell-sample-editor-grid.js" type="text/javascript"></script>
    <script src="ext-3.2.1/ux/FileUploadField.js" type="text/javascript"></script>
    <!-- script src="ext-3.2.1/ux/CheckColumn.js" type="text/javascript"></script -->
    <script src="client-scripts/new-cellline-form.js" type="text/javascript"></script>

	<script src="ext-3.2.1/ux/RowEditor.js" type="text/javascript"></script>
    
    <script type="text/javascript">
            
        Ext.onReady(function() {
            
		var editor = new Ext.ux.grid.RowEditor({
        		saveText: 'Update',
			listeners: {
				beforeedit: function(editor, index) {
					var record = this.grid.store.getAt(index);
					return record.get('Box') !== '';
			
				}			
			}
    		});

            // useful function for converting a numeric index to a alphanumeric coordinate,
            // eg 1 -> A1, 10 -> A10, 11 -> B1 etc
            Ext.slims.coordinate = function(index) {
                return String.fromCharCode(Math.floor(index / 10) + 'A'.charCodeAt(0)) + (index % 10 + 1);
            }
            
            // create a standalone instance of the cell line store - this will be used in more than one control.
            var celllinestore = new Ext.data.XmlStore({
                id: 'Name',    
                url: 'queries/get-celllines.php',
                record: 'row',
                fields: [
                            'Name',
                            'PassageTechnique',
                            'TissueName',
                            'SpeciesName',
                            'Description',
                            'Morphology',
                            'GrowthMode',
                            'Kayrotype',
                            {name: 'NegativeMycoplasmaTestDate', type: 'date'},
                            {name: 'DocumentCount', type: 'number'}
                        ]
            });
             
            // the whole GUI is created in a ViewPort, with the typical tab border-laid out with a form to the west and a grid in the center
            var view = new Ext.Viewport({
                renderTo: Ext.getBody(),
                layout: 'border',
                items: [ new Ext.BoxComponent({ // raw element
                    region:'north',
                    el: 'header',
                    height:32
                })
                ,{
                    id: 'main',
                    region: 'center',
                    activeTab: 0,
                    xtype: 'tabpanel',
                    listeners: {
                        afterrender: {
                            fn: function() {
                                // after this tabpanel is drawn add a logout button and a wee welcome message with the user's name.
                                var headers = Ext.select('#main div.x-tab-strip-wrap');
                                if(!headers || headers.length === 0)
                                    throw new Error();
                                    
                                var name = Ext.DomHelper.createDom({
                                    tag: 'span',
                                    id: 'tabpanel-name',
                                    html: 'Welcome<?php echo $id ? ", <b>".$id."</b>" : ""; ?>'
                                });
                                headers.appendChild(name);
                                // hyperlink is added to the far-right (see css)
                                var a = Ext.DomHelper.createDom({tag:'a', id:'tabpanel-logout-link', cls: 'slims-tabpanel-link', html: 'Log out' });
                                // this should point to the raven logout url
                                a.setAttribute('href','logout.php');
                                headers.appendChild(a);
                            }
                        }
                    },
                    items: [{
                        id: 'view-contents-tab',
                        layout: 'border',
                        title: 'Contents',
                        frame: false,
                        border: false,
                        locationHandler: function() {
                            // when the location selection changes and the update grid button is pressed,
                            // reload the grid's store
                            var form = Ext.getCmp('view-contents-location-form');
                            // collect all the values from the location form
                            var p = form.getPositions(),
                                d = form.getDewar(),
                                s = form.getStack(),
                                b = form.getBox();
                            // clear the grid's current state
                            var grid = Ext.getCmp('view-contents-grid');
                            var store = grid.getStore();
                            store.removeAll();
                            
                            // make sure the location data's useable, otherwise do nothing
                            if(!d || !s || !b || !p || p.length === 0) 
                                return;
                            // positions are used as a +-delimited string
                            p = p.join('+');
                            
                            var mask = new Ext.LoadMask(grid.getEl(), {
                                msg: 'Loading...',
                                store: store,
                                removeMask: true
                            });
                                                        
                            // load the data into the store with the new location parameters
                            store.load({
                                params: {
                                    search: 0, // using the location form, not search
                                    dewar: d,
                                    stack: s,
                                    box: b,
                                    positions: p
                                }
                            });
                        },
                        searchHandler: function() {
                            // handle the search scenario
                            // plenty of different options for this one
                            
                            // if search term is invalid, do nothing
                            var term = Ext.getCmp('view-contents-search-term').getValue();
                            if(!term || term.length === 0)
                                return;
                            
                            // determine which fields to search. if no field or "all fields" selected,
                            // get all possible fields from the combobox and add them to the fields collection
                            var fields = [];
                            var selected = Ext.getCmp('view-contents-field-radio-group').getValue();
                            var fieldcombo = Ext.getCmp('view-contents-search-field-selector-combo');
                            if(!selected || selected.getId() === 'view-contents-search-all-fields-radio') {
                                fieldcombo.getStore().each(function(record) {
                                    fields.push(record.get('field1'));
                                });
                            } else if(selected.getId() === 'view-contents-search-single-field-radio') {
                                // only a particular field, but still use array.
                                fields.push(fieldcombo.getValue());
                            }
                            // single record checkbox: only return a single record from each box in which a search is successful 
                            var singlerecord = Ext.getCmp('view-contents-single-record-check').getValue() ? 1 : 0;
                            var maxrecords = Ext.getCmp('view-contents-max-records-combo').getValue() || 100; // record limit (100 default)
                            var recordsradio = Ext.getCmp('view-contents-sample-owner-radiogroup').getValue(); // only search user's sample 
                            var recordschoice = 0;
                            if(recordsradio && recordsradio.getId() === 'view-content-group-sample-radio') {
                                recordschoice = 1;
                            }
                            
                            // load the search data into the grid
                            var grid = Ext.getCmp('view-contents-grid');
                            var store = grid.getStore();
                            store.removeAll();
                            var mask = new Ext.LoadMask(grid.getEl(), {
                                msg: 'Searching...',
                                store: store,
                                removeMask: true
                            });
                            mask.show();
                            store.load({
                                params: {
                                    term: term,
                                    search: 1, // using search, not location form
                                    fields: fields.join('+'), // +-delimited string
                                    'single-record': singlerecord,
                                    'max-records': maxrecords,
                                    'group-records': recordschoice
                                }
                            });
                        },
                        items: [{
                            xtype: 'panel',
                            layout: 'form',
                            region: 'west',
                            width: 350,
                            frame: false,
                            border: true,
                            autoScroll: true,
                            tbar: [{
                                xtype: 'radiogroup',
                                width: 200,
                                items: [{
                                    xtype: 'radio',
                                    boxLabel: 'Location form',
                                    name: 'view-content-method',
                                    checked: true,

                                    listeners: {
                                        check: {
                                            fn: function(radio, checked) {
                                                Ext.getCmp('view-contents-location-form').setDisabled(!checked);
                                                if(checked) {
                                                    Ext.getCmp('view-contents-grid-update-button').setHandler(Ext.getCmp('view-contents-tab').locationHandler);
                                                }
                                            }
                                        }
                                    }
                                },{
                                    xtype: 'radio',
                                    boxLabel: 'Search',
                                    name: 'view-content-method',
                                    listeners: {
                                        check: {
                                            fn: function(radio, checked) {
                                                Ext.getCmp('view-content-search-panel').setDisabled(!checked);
                                                if(checked) {
                                                    Ext.getCmp('view-contents-grid-update-button').setHandler(Ext.getCmp('view-contents-tab').searchHandler);
                                                }
                                            }
                                        }
                                    }                                    
                                }]
                            }],
                            items: [{
                                id: 'view-contents-location-form',
                                frame: false,
                                border: false,
                                xtype: 'locationform',
                                selectType: 'full'
                            },{
                                xtype: 'spacer',
                                style: 'background: #99bbe8; margin: 5px;',
                                height: 1
                            },{
                                id: 'view-content-search-panel',
                                xtype: 'panel',
                                disabled: true,
                                layout: 'form',
                                border: false,
                                bodyStyle: 'padding: 5px;',
                                items:[{
                                    id: 'view-contents-search-term',
                                    xtype: 'textfield',
                                    fieldLabel: 'Search',
                                    width: 215
                                },{
                                    id: 'view-contents-field-radio-group',
                                    xtype: 'radiogroup',
                                    vertical: true,
                                    columns: 1,
                                    items:[{
                                        id: 'view-contents-search-all-fields-radio',
                                        xtype: 'radio',
                                        boxLabel: 'All fields',
                                        name: 'search-field',
                                        checked: 'true'
                                    },{
                                        xtype: 'panel',
                                        layout: 'hbox',
                                        border: false,
                                       
                                        items: [{
                                            id: 'view-contents-search-single-field-radio',
                                            xtype: 'radio',
                                            boxLabel: 'Field',
                                            name: 'search-field',
                                            
                                            listeners: {
                                                check: function(radio, checked) {
                                                    Ext.getCmp('view-contents-search-field-selector-combo').setDisabled(!checked);
                                                }
                                            }
                                        },{
                                            id: 'view-contents-search-field-selector-combo',
                                            xtype: 'combo',
                                            mode: 'local',
                                            style: 'margin-left: 5px;',
                                            store: [
                                                    ['CellLine','Cell Line Name'],
                                                    ['Comment','Sample Comment'],
                                                    ['Description', 'Cell Line Description'],
                                                    ['Tissue','Tissue'],
                                                    ['Species','Species'],
                                                    ['Morphology','Morphology'],
                                                    ['BasalMedia','Basal Media'],
                                                    ['PassageNumber','Passage Number'],
                                                    ['PassageTechnique','Passage Technique'],
                                                    ['GrowthMode','Growth Mode'],
                                                    ['Kayrotype','Kayrotype']
                                            ],
                                            valueField: 'id',
                                            displayField: 'field',
                                            triggerAction: 'all',
                                            disabled: true
                                        }]
                                    }]
                                },{
                                    id: 'view-contents-single-record-check',
                                    xtype: 'checkbox',
                                    boxLabel: 'Return single record per box'
                                },{
                                    id: 'view-contents-sample-owner-radiogroup',
                                    xtype: 'radiogroup',
                                    columns: 1,
                                    style: 'background-color: #ebf3fd;',
                                    items: [{
                                        id: 'view-contents-all-sample-radio',
                                        xtype: 'radio',
                                        name: 'view-contents-sample-owner',
                                        boxLabel: 'All samples',
                                        checked: true
                                    },{
                                        id: 'view-content-group-sample-radio',
                                        xtype: 'radio',
                                        name: 'view-contents-sample-owner',
                                        boxLabel: 'Group samples'
                                    }]
                                },{
                                    id: 'view-contents-max-records-combo',
                                    xtype: 'combo',
                                    fieldLabel: 'Maximum number of records',
                                    mode: 'local',
                                    store: [1,5,10,20,50,100],
                                    value: 100,
                                    width: 50,
                                    triggerAction: 'all'
                                }],
                                buttons: [{
                                    text: 'Clear',
                                    style: 'margin-right: 23px;'
                                }]
                            }]
                        },{
                            id: 'view-contents-grid',
                            region: 'center',
                            xtype: 'grid',
                            frame: false,
                            border: true,
                            mode: 'remote',
                            viewConfig: {
                                emptyText: 'No records found'  
                            },
                            listeners: {
                                render: {
                                    fn: function(grid) {
                                        grid.getSelectionModel().lock();
                                    }
                                }
                            },
                            tbar: [{
                                id: 'view-contents-grid-update-button',
                                text: 'Update Selection',
                                handler: function() { Ext.getCmp('view-contents-tab').locationHandler(); }
                            },{
                                xtype: 'tbseparator'
                            },{
                                id: 'view-contents-grid-copy-to-button',
                                text: 'Copy result(s) to',
                                disabled: true
                            },{
                                xtype: 'radiogroup',
                                width: '18em',
                                disabled: true,
                                items: [{
                                    xtype: 'radio',
                                    boxLabel: 'Add Sample',
                                    name: 'view-contents-copy-to',
                                    checked: true
                                },{
                                    xtype: 'radio',
                                    boxLabel: 'Remove Sample',
                                    name: 'view-contents-copy-to'
                                }]
                            }, {
                                xtype: 'tbseparator'
                            }, {
                                id: 'view-contents-export-excel-button',
                                // disabled: true,
                                xtype: 'button',
                                text: 'Export to Excel',
                                handler: function() {
                                    if(!Ext.slims.ExportWindow) {
                                        Ext.slims.ExportWindow = new Ext.Window({
                                            title: 'Export to Excel',
                                            layout: 'fit',
                                            resizable: false,
                                            closeAction: 'hide',
                                            modal: true,
                                            width: 500,
                                            height: 200,
                                            buttons: [{
                                                text: 'Cancel',
                                                handler: function() {
                                                    Ext.slims.ExportWindow.hide();
                                                }
                                            },{
                                                text: 'Export',
                                                handler: function() {
                                                    var gridstore = Ext.getCmp('view-contents-grid').getStore();
                                                    var isall = Ext.getCmp('export-selection-option-radio-all').getValue();
                                                    if(gridstore.getCount() === 0 && !isall)
                                                        return;
                                                    var helper = Ext.DomHelper;
                                                    var selectcombo = Ext.getCmp('export-selection-option-combo');
                                                    var islist = Ext.getCmp('export-output-style-list-radio').getValue();
                                                    var formspec = {
                                                        tag: 'form',
                                                        cls: 'not-displayed',
                                                        action: 'queries/create-excel-workbook.php',
                                                        target: '_blank',
                                                        method: 'post',
                                                        children: [{
                                                            tag: 'input',
                                                            type: 'hidden',
                                                            name: 'all',
                                                            value: isall ? 1 : 0
                                                        }, {
                                                            tag: 'input',
                                                            type: 'hidden',
                                                            name: 'selection',
                                                            value: isall ? '' : selectcombo.getValue()
                                                        }, {
                                                            tag: 'input',
                                                            type: 'hidden',
                                                            name: 'export-type',
                                                            value: islist ? 'list' : 'grid'
                                                        }]
                                                    };
                                                    if(gridstore.getCount() !== 0) {
                                                        var store = new Ext.data.ArrayStore({
                                                            fields: ['Dewar', 'Stack', 'Box', 'Position'],
                                                            mode: 'local',
                                                            record: 'row', // necessary for the writer's XML output
                                                            writer: new Ext.data.XmlWriter({
                                                                xmlEncoding: 'utf-8',
                                                                writeAllFields: true,
                                                                idProperty: 'id',
                                                                documentRoot: 'xrequest',
                                                                root: 'records',
                                                                forceDocumentRoot: true
                                                            })
                                                        });
                                                        var writer = store.writer;
                                                        var params = {};
                                                        var s = gridstore.getRange();
                                                        var records = [];
                                                        for(var i = 0, count = gridstore.getCount(); i < count; i++) {
                                                            var r = gridstore.getAt(i);
                                                            var record = new store.recordType({
                                                                Dewar: r.data.Dewar,
                                                                Stack: r.data.Stack,
                                                                Box: r.data.Box,
                                                                Position: r.data.Position
                                                            }, Ext.id());
                                                            records.push(record);
                                                        }
                                                        writer.apply(params, {}, "create", records);
                                                        
                                                        var xml = encodeURIComponent(params.xmlData);
                                                        formspec.children.push({
                                                           tag: 'input',
                                                           type: 'hidden',
                                                           name: 'positions',
                                                           value: xml
                                                        });
                                                    }
                                                    var form = helper.append(Ext.getBody(), formspec);
                                                    form.submit();
                                                }
                                            }],
                                            items: [{
                                                xtype: 'panel',
                                                bodyStyle: 'padding: 5px',
                                                layout: 'form',
                                                items: [{
                                                    xtype: 'fieldset',
                                                    items: [{
                                                        fieldLabel: 'Export',
                                                        id: 'export-selection-option-radio-group',
                                                        xtype: 'radiogroup',
                                                        columns: 1,
                                                        items: [new Ext.form.Radio({
                                                            xtype: 'radio',
                                                            boxLabel: 'All',
                                                            name: 'export-selection-radio',
                                                            id: 'export-selection-option-radio-all'
                                                        }),{
                                                            xtype: 'panel',
                                                            layout: 'hbox',
                                                            border: false,
                                                            defaults: { hideLabel: true },
                                                            items: [new Ext.form.Radio({
                                                                xtype: 'radio',
                                                                boxLabel: 'Current Selected',
                                                                name: 'export-selection-radio',
                                                                id: 'export-selection-option-radio-selected',
                                                                checked: true,
                                                                listeners: {
                                                                    check: {
                                                                        fn: function(radio, checked) {
                                                                            var combo = Ext.getCmp('export-selection-option-combo');
                                                                            combo.setDisabled(combo.getStore().getCount() === 0 || !checked);
                                                                        }
                                                                    }
                                                                }
                                                            }), new Ext.form.ComboBox({
                                                                id: 'export-selection-option-combo',
                                                                xtype: 'combo',
                                                                style: 'margin-left: 5px',
                                                                width: 215,
                                                                store: [ 'Dewar(s)', 'Stack(s)', 'Box(es)', 'Position(s)' ],
                                                                value: 'Position(s)',
                                                                mode: 'local',
                                                                triggerAction: 'all',
                                                                forceSelection: true,
                                                                editable: false,
                                                                lastQuery: ''
                                                            })]
                                                        }]
                                                    },{
                                                        xtype: 'radiogroup',
                                                        columns: 1,
                                                        fieldLabel: 'Export style',
                                                        items: [{
                                                            id: 'export-output-style-list-radio',
                                                            xtype: 'radio',
                                                            boxLabel: 'List',
                                                            name: 'export-output-style',
                                                            checked: true
                                                        },{
                                                            id:  'export-output-style-grid-radio',
                                                            xtype: 'radio',
                                                            boxLabel: 'Grid',
                                                            name: 'export-output-style',
                                                            disabled: true // not supported yet
                                                        }]
                                                    }]
                                                }]
                                            }]
                                        });
                                    }
                                    var grid = Ext.getCmp('view-contents-grid');
                                    var gridstore = grid.getStore();
                                    var combo = Ext.getCmp('export-selection-option-combo');
                                    var selectstore = combo.getStore();
                                    selectstore.clearFilter();
                                    var hasrecords = (gridstore.getCount() !== 0);
                                    var selectradio = Ext.getCmp('export-selection-option-radio-selected');
                                    selectradio.setValue(hasrecords);
                                    selectradio.setDisabled(!hasrecords);
                                    Ext.getCmp('export-selection-option-radio-all').setValue(!hasrecords);
                                    combo.setDisabled(!hasrecords);
                                    
                                    var exportwin = Ext.slims.ExportWindow;
                                    exportwin.show();
                                }
                            }],
                            
                            store: new Ext.data.Store({
                                storeId: 'view-contents-store',
                                reader: new Ext.data.XmlReader({
                                    record: 'row',
                                    successProperty: '@success',
                                    fields: [
                                                'Dewar',
                                                'Stack',
                                                'Box',
                                                {name: 'Position', type: 'number'},
                                                'CellLine',
                                                'PassageNumber',
                                                'BasalMedia',
                                                'Comment',
                                                'PassageTechnique',
                                                'Tissue',
                                                'Species',
                                                'GrowthMode',
                                                'Description',
                                                'Morphology',
                                                'Kayrotype',
                                                'NegativeMycoplasmaTestDate',
                                                'ResearchGroup'
                                            ]
                                }),
                                sortInfo: {field:'Position', direction:'ASC'},
                                autoLoad: false,
                                url: 'queries/get-content.php',
                                listeners: {
                                    exception: {
                                        fn: function(misc) {
                                            var reader = new Ext.slims.XmlReader();
                                            var data = reader.read();
                                        }
                                    },
                                    load: {
                                        fn: function(store, records, options) {
                                            // store.updateExportButton(store);
                                        }
                                    },
                                    add: {
                                        fn: function(store, records, index) {
                                            // store.updateExportButton(store);
                                        }
                                    },
                                    remove: {
                                        fn: function(store, records, index) {
                                            // store.updateExportButton(store);
                                        }
                                    },
                                    clear: {
                                        fn: function(store, records) {
                                            // store.updateExportButton(store);
                                        }
                                    }
                                },
                                updateExportButton: function(store) {
                                    var button = Ext.getCmp('view-contents-export-excel-button');
                                    button.setDisabled(store.getCount() === 0);
                                },
                                writer: new Ext.data.XmlWriter({
                                    xmlEncoding: 'utf-8',
                                    writeAllFields: true,
                                    idProperty: 'id',
                                    documentRoot: 'xrequest',
                                    root: 'records',
                                    forceDocumentRoot: true
                                })
                            }),
                            columns: [{
                                header: 'Dewar',
                                dataIndex: 'Dewar',
                                hidden: false
                            },{
                                header: 'Stack',
                                dataIndex: 'Stack',
                                hidden: false
                            },{
                                header: 'Box',
                                dataIndex: 'Box',
                                hidden: false
                            },{
                                header: 'Position',
                                dataIndex: 'Position',
                                renderer: function(value) {
                                    return Ext.slims.coordinate(value - 1);
                                }
                            },{
                                header: 'Cell Line Name',
                                dataIndex: 'CellLine'
                            },{
                                header: 'Passage Number',
                                dataIndex: 'BasalMedia'
                            },{
                                header: 'Comment',
                                dataIndex: 'Comment'
                            },{
                                header: 'Passage Technique',
                                dataIndex: 'PassageTechnique'
                            },{
                                header: 'Tissue',
                                dataIndex: 'Tissue'
                            },{
                                header: 'Species',
                                dataIndex: 'Species'
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
                                xtype: 'datecolumn',
                                format: 'd/m/Y'
                            },{
                                header: 'Research Group',
                                dataIndex: 'ResearchGroup',
                                hidden: true
                            }]
                        }]
                    },
                    <?php if($isvalid) { ?>
                    {
                        id: 'add-sample-tab',
                        frame: false,
                        title: 'Add Sample',
                        layout: 'fit',
                        items: [{
                            layout: 'border',
                            border: false,
                            items: [{
                                xtype: 'panel',
                                region: 'west',
                                layout: 'form',
                                border: true,
                                width: 350,
                                autoScroll: true,
                                items: [{
                                    id: 'location-form',
                                    xtype: 'locationform',
                                    selectType: 'empty',
                                    grouponly: 1 // only retrieve stacks  to which this user has access
                                    // ,autoScroll: true
                                },{
                                    xtype: 'spacer',
                                    height: 1
                                },{
                                    id: 'cellline-form',
                                    xtype: 'celllineform',
                                    cellLineStore: celllinestore,
                                    newCellLineButtonHandler: function() {
                                        Ext.getCmp('main').setActiveTab(2);
                                    }
                                }]
                            },{
                                id: 'sample-editor',
                                xtype: 'sample-editor',
                                region: 'center',
                                celllineEditor: 'cellname',
                                passageNumberEditor: 'passage-number',
                                basalMediaEditor: 'basal-media-combo',
                                commentEditor: 'comment',
                                dateEditor: 'operation-date',
                                operationEditor: 'operation',
                                userEditor: 'operation-user-combo',
                                addSelectionHandler: function() {
                                    this.clear();
                                    var location = Ext.getCmp('location-form');
                                    var cellline = Ext.getCmp('cellline-form');
                                    var positions = location.getPositions();
                                    if(positions && positions.length !== 0) {
                                        var dewar = location.getDewar();
                                        var stack = location.getStack();
                                        var box = location.getBox();
                                        var celllinename = cellline.getCellLineName();
                                        var passagenumber = cellline.getPassageNumber();
                                        var basalmedia = cellline.getBasalMedia();
                                        var opdate = cellline.getOperationDate();
                                        var operation = cellline.getOperation();
                                        var comment = cellline.getComment();
                                        var user = cellline.getOperationUser();
                                        
                                        for(var i = 0, length = positions.length; i < length; i++) {
                                            var position = positions[i];
                                            var record = {
                                                id: dewar + "-" + stack + "-" + box + "-" + position,
                                                Dewar: dewar,
                                                Stack: stack,
                                                Box: box,
                                                Position: position,
                                                'Cell-Line': celllinename,
                                                'Passage-Number': passagenumber,
                                                'Basal-Media': basalmedia,
                                                Comment: comment,
                                                User: user,
                                                'Operation-Date': opdate,
                                                Operation: operation
                                            };
                                            this.addRecord(record);
                                        }
                                    }
                                }
                            }]
                        }]
                    },{
                        id: 'new-cellline-tab',
                        title: 'New Cell Line',
                        layout: 'fit',
                        frame: false,
                        border: false,
                        items: [{
                            layout: 'fit',
                            id: 'new-cellline-form',
                            xtype: 'new-cellline-form',
                            cellLineStore: celllinestore
                        }]
                    },{
                        id: 'remove-sample-tab',
                        layout: 'border',
                        title: 'Remove Sample',
                        frame: false,
                        border: false,
                        height: 668,
                        items: [{
                            layout: 'form',
                            frame: false,
                            border: true,
                            region: 'west',
                            width: 350,
                            labelWidth: 100,
                            autoScroll: true,
                            items: [{
                                id: 'remove-sample-location-form',
                                frame: false,
                                border: false,
                                xtype: 'locationform',
                                selectType: 'full',
                                'slims-group': 2
                            }, new Ext.form.ComboBox({
                                id: 'remove-sample-activity-user',
                                xtype: 'combo',
                                fieldLabel: 'User',
                                mode: 'remote',
                                displayField: 'Staff',
                                store: {
                                    xtype: 'xmlstore',
                                    storeId: 'remove-sample-operation-user-store',
                                    url: 'queries/get-activity-users-xml.php',
                                    fields: ['Staff', 'ResearchGroup'],
                                    record: 'row'
                                },
                                labelStyle: 'padding-left: 5px',
                                width: 215,
                                triggerAction: 'all',
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
                            }),{
                                id: 'remove-sample-activity-date',
                                xtype: 'datefield',
                                value: new Date(),
                                format: 'd/m/Y',
                                fieldLabel: 'Date',
                                width: 215,
                                labelStyle: 'padding-left: 5px'
                            },{
                                id: 'remove-sample-operation',
                                xtype: 'combo',
                                mode: 'local',
                                fieldLabel: 'Operation',
                                valueField: 'id',
                                displayField: 'operation',
                                disabled: false,
                                editable: false,
                                submitValue: true,
                                // value: 2, // thaw
                                value : 'Thaw',
                                width: 215,
                                labelStyle: 'padding-left: 5px',
                                store: new Ext.data.ArrayStore({
                                    idIndex: 0,
                                    fields: ['id','operation'],
                                    data: [[2,'Thaw']]
                                })
                            }]
                        },{
                            id: 'remove-sample-grid',
                            xtype: 'editorgrid',
                            region: 'center',
                            tbar: [{
                                text: 'Update Selection',
                                handler: function() {
                                    var form = Ext.getCmp('remove-sample-location-form');
                                    var p = form.getPositions(),
                                        d = form.getDewar(),
                                        s = form.getStack(),
                                        b = form.getBox(),
                                        o = Ext.getCmp('remove-sample-operation').getValue(),
                                        u = Ext.getCmp('remove-sample-activity-user').getValue();
                                    p = p.join('+');
                                    var grid = Ext.getCmp('remove-sample-grid');
                                    var store = grid.getStore();
                                    var mask = new Ext.LoadMask(grid.getEl(), {
                                        msg: 'Loading...'
                                    });
                                    mask.show();
                                    store.load({
                                        params: {
                                            dewar: d,
                                            stack: s,
                                            box: b,
                                            positions: p
                                        },
                                        callback: function(records, options, success) {
                                            mask.hide();
                                            var v = Ext.getCmp('remove-sample-activity-date').value;
                                            var _d = v.split('/');
                                            var dt = new Date(_d[2], parseInt(_d[1]) - 1, _d[0]);
                                            store.each(function(record) {
                                                record.data.Dewar = d;
                                                record.data.Stack = s;
                                                record.data.Box = b;
                                                record.data.ThawDate = dt;
                                                record.data.Operation = o;
                                                record.data['ActivityDate-Time'] = dt.getTime() / 1000;
                                                record.data['OperationUser'] = u;
                                            });
                                            Ext.getCmp('remove-sample-remove-sample-button').setDisabled(store.getCount() === 0);
                                            grid.getView().refresh();
                                        }
                                    });
                                }
                            },{
                                xtype: 'tbseparator'
                            },{
                                id: 'remove-sample-remove-sample-button',
                                text: 'Remove Selected Sample(s)',
                                disabled: true,
                                handler: function() {
                                    var editor = Ext.getCmp('remove-sample-grid');
                                    var store = editor.getStore();
                                    var c = store.getCount();
                                    if(c === 0)
                                        return false;
                                    
                                    var params = {};
                                    var writer = store.writer;
                                    var s = [];
                                    for(var i = 0; i < c; i++) {
                                        s.push(store.getAt(i));
                                    }
                                    writer.apply(params, {}, "create", s);
                                    var mask = new Ext.LoadMask(editor.getEl(), {
                                        msg: 'Saving...'
                                    });
                                                                    
                                    mask.show();                                   
                                    
                                    Ext.Ajax.request({
                                        url: 'queries/remove-sample-xml.php',
                                        method: 'post',
                                        params: params,
                                        callback: function(options, success, response) {
                                            mask.hide();
                                            var reader = new Ext.slims.XmlReader();
                                            var data = reader.read(response);
                                            if(data.success) {
                                                Ext.Msg.show({
                                                    title: 'Remove Sample: Success',
                                                    msg: 'Records removed successfully',
                                                    icon: Ext.MessageBox.INFO,
                                                    buttons: Ext.Msg.OK,
                                                    fn: function() {
                                                        Ext.getCmp('remove-sample-location-form').refresh();
                                                        store.removeAll();
                                                    }
                                                });
                                                
                                            } else {
                                                Ext.Msg.show({
                                                    title: 'Remove Sample: Fail',
                                                    msg: 'Error removing records. No records removed<br/>' + (function() { var s; for(var i = 0, s = ''; i < data.records.length; s += '<br/>' + data.records[i].data.message, i++); return s; })(),
                                                    icon: Ext.MessageBox.ERROR,
                                                    buttons: Ext.Msg.OK
                                                });
                                            }
                                        }
                                    });
                                    return true;
                                }
                            }],
                            store: new Ext.data.XmlStore({
                                fields: [
                                    'Dewar',
                                    'Stack',
                                    'Box',
                                    {name: 'Position', type: 'number' },
                                    'CellLine',
                                    { name: 'ActivityDate', type: 'date', format: 'd/m/Y' },
                                    { name: 'ThawDate', type: 'date', format: 'd/m/Y'},
                                    'ActivityDate-Time',
                                    'OperationUser',
                                    'Operation'
                                ],
                                url: 'queries/get-content.php',
                                record: 'row',
                                successProperty: '@success',
                                autoLoad: false,
                                sortInfo: {field:'Position', direction:'ASC'},
                                writer: new Ext.data.XmlWriter({
                                    writeAllFields: true,
                                    idProperty: 'id',
                                    documentRoot: 'xrequest',
                                    root: 'records',
                                    forceDocumentRoot: true
                                })
                            }),
                            autoExpandColumn: 'remove-sample-cellline-column',
                            columns: [
                            {
                                header: 'Dewar',
                                dataIndex: 'Dewar',
                                hidden: true
                            },{
                                header: 'Stack',
                                dataIndex: 'Stack',
                                hidden: true
                            },{
                                header: 'Box',
                                dataIndex: 'Box',
                                hidden: true
                            },{
                                id: 'remove-sample-position-column',
                                header: 'Position',
                                dataIndex: 'Position',
                                renderer: function(value) {
                                    return Ext.slims.coordinate(value - 1);
                                },
                                sortable: true,
                                editable: false
                            },{
                                id: 'remove-sample-cellline-column',
                                header: 'Cell Line',
                                dataIndex: 'CellLine',
                                sortable: true,
                                editable: false
                            },{
                                header: 'Freeze Date',
                                xtype: 'datecolumn',
                                format: 'd/m/Y',
                                dataIndex: 'ActivityDate',
                                sortable: true,
                                editable: false
                            },{
                                header: 'Operation',
                                dataIndex: 'Operation',
                                hidden: true
                            },{
                                header: 'User',
                                dataIndex: 'OperationUser',
                                editor: new Ext.form.ComboBox(Ext.getCmp('remove-sample-activity-user').cloneConfig())
                            },{
                                header: 'Date',
                                xtype: 'datecolumn',
                                format: 'd/m/Y',
                                dataIndex: 'ThawDate',
                                editable: true,
                                editor: new Ext.form.DateField()
                            }]
                        }]
                    }<?php if($isadmin) { ?>
                     ,{
                        id: 'users-tab',
                        layout: 'border',
                        title: 'Users',
                        //height: 668,
                        
                        items: [{
                            id: 'users-groups-form',
                            layout: 'form',
                            region: 'west',
                            width: 350,
                            labelWidth: 100,
                            bodyStyle: 'padding: 5px;',
                            defaults: { width: 215 },
                            items: [{
                                id: 'users-form-id',
                                xtype: 'textfield',
                                fieldLabel: 'ID',
                                emptyText: 'Enter the user\'s Raven ID'
                            },{
                                id: 'users-form-name',
                                xtype: 'textfield',
                                fieldLabel: 'Name',
                                emptyText: 'Enter the user\'s full name'
                            },{
                                xtype: 'panel',
                                layout: 'hbox',
                                fieldLabel: 'Group',
                                border: false,
                                frame: false,
                                items: [{
                                    id: 'users-groups-combo',
                                    xtype: 'combo',
                                    triggerAction: 'all',
                                    mode: 'remote',
                                    typeAhead: false,
                                    forceSelection: true,
                                    emptyText: 'Choose user\'s group',
                                    store: new Ext.data.XmlStore({
                                        storeId: 'users-groups-store',
                                        idIndex: 0,
                                        fields: ['ResearchGroupID', 'ResearchGroup', 'DepartmentID', 'Department', { name: 'IsAdmin', type: 'bool'}],
                                        record: 'row',
                                        url: 'queries/get-researchgroups-xml.php',
                                        sortInfo: {
                                            field: 'ResearchGroup',
                                            direction: 'ASC'
                                        }
                                    }),
                                    displayField: 'ResearchGroup',
                                    valueField: 'ResearchGroupID'
                                },{
                                    xtype: 'button',
                                    text: 'New',
                                    handler: function() {
                                        var showWindow = function() {
                                            if(!window.groupwin)
                                                window.groupwin = new Ext.Window({
                                                    title: 'Add New Group',
                                                    closeAction: 'hide',
                                                    width: 350,
                                                    modal: true,
                                                    resizable: false,
                                                    buttons: [{
                                                        text: 'Cancel',
                                                        handler: function() {
                                                            window.groupwin.hide();
                                                        }
                                                    },{
                                                        text: 'Apply',
                                                        handler: function() {
                                                            var group = Ext.getCmp('users-groups-form-textbox').getValue();
                                                            if(!group || group.length === 0)
                                                                return;
                                                            var dept = Ext.getCmp('users-groups-form-department-combo').getValue();
                                                            if(typeof dept === 'undefined' || dept == null)
                                                                return;
                                                            var admin = Ext.getCmp('users-groups-form-isadmin-checkbox').getValue();
                                                            var gstore = Ext.StoreMgr.get('users-groups-store');
                                                            if(gstore.find('ResearchGroup', group, 0, false, false) !== -1)
                                                                return;
                                                            
                                                            var mask = new Ext.LoadMask(window.groupwin.getEl(), {
                                                                msg: 'Saving...'
                                                            });
                                                                    
                                                            mask.show();
                                    
                                                            Ext.Ajax.request({
                                                                url: 'queries/add-user-group.php',
                                                                params: {
                                                                    ResearchGroup: group,
                                                                    Department: dept,
                                                                    IsAdmin: admin ? 1 : 0
                                                                },
                                                                method: 'post',
                                                                callback: function(options, success, response) {
                                                                    mask.hide();
                                                                    var reader = new Ext.slims.XmlReader();
                                                                    var data = reader.read(response);
                                                                    if(data.success) {
                                                                        gstore.reload({
                                                                            callback: function() {
                                                                                mask.hide();
                                                                                
                                                                                Ext.Msg.show({
                                                                                    title: 'Add Group: Success',
                                                                                    msg: 'Record added successfully',
                                                                                    icon: Ext.MessageBox.INFO,
                                                                                    buttons: Ext.Msg.OK,
                                                                                    fn: function() {
                                                                                        Ext.getCmp('users-groups-form-textbox').reset();
                                                                                        Ext.getCmp('users-groups-form-department-combo').reset();
                                                                                        Ext.getCmp('users-groups-form-isadmin-checkbox').reset();
                                                                                    }
                                                                                });
                                                                            }
                                                                        });
                                                                    } else {
                                                                        mask.hide();
                                                                        Ext.Msg.show({
                                                                            title: 'Add Group: Fail',
                                                                            msg: 'Error adding record. No record added<br/>' + (function() { var s; for(var i = 0, s = ''; i < data.records.length; s += '<br/>' + data.records[i].data.message, i++); return s; })(),
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
                                                            id: 'users-groups-form-list',
                                                            fieldLabel: 'Groups',
                                                            xtype: 'listview',
                                                            autoScroll: true,
                                                            height: 300,
                                                            store: Ext.StoreMgr.get('users-groups-store'),
                                                            columns: [{
                                                                header: 'Name',
                                                                dataIndex: 'ResearchGroup'
                                                            },{
                                                                header: 'Department',
                                                                dataIndex: 'Department'
                                                            }]
                                                        },{
                                                            id: 'users-groups-form-textbox',
                                                            xtype: 'textfield',
                                                            fieldLabel: 'Group Name'
                                                        },{
                                                            xtype: 'panel',
                                                            layout: 'hbox',
                                                            fieldLabel: 'Department',
                                                            width: 200,
                                                            border: false,
                                                            frame: false,
                                                            items: [{
                                                                    id: 'users-groups-form-department-combo',
                                                                    xtype: 'combo',
                                                                    store: new Ext.data.XmlStore({
                                                                        storeId: 'users-departments-store',
                                                                        idIndex: 0,
                                                                        fields: ['DepartmentID', 'Department',],
                                                                        record: 'row',
                                                                        url: 'queries/get-departments-xml.php',
                                                                        sortInfo: {
                                                                            field: 'Department',
                                                                            direction: 'ASC'
                                                                        }
                                                                    }),
                                                                    displayField: 'Department',
                                                                    valueField: 'DepartmentID',
                                                                    triggerAction: 'all'
                                                                },{
                                                                    xtype: 'button',
                                                                    text: 'New',
                                                                    handler: function() {
                                                                        var showDeptWindow = function() {
                                                                            if(!window.deptwin)
                                                                                window.deptwin = new Ext.Window({
                                                                                    title: 'Add New Department',
                                                                                    closeAction: 'hide',
                                                                                    width: 350,
                                                                                    modal: true,
                                                                                    resizable: false,
                                                                                    buttons: [{
                                                                                        text: 'Cancel',
                                                                                        handler: function() {
                                                                                            window.deptwin.hide();
                                                                                        }
                                                                                    },{
                                                                                        text: 'Apply',
                                                                                        handler: function() {
                                                                                            var dept = Ext.getCmp('users-departments-form-textbox').getValue();
                                                                                            if(!dept || dept.length === 0)
                                                                                                return;
                                                                                            var mask = new Ext.LoadMask(window.deptwin.getEl(), {
                                                                                                msg: 'Saving...'
                                                                                            });
                                                                                                    
                                                                                            mask.show();
                                                                                             
                                                                                            Ext.Ajax.request({
                                                                                                url: 'queries/add-department.php',
                                                                                                params: { Department: dept},
                                                                                                method: 'post',
                                                                                                callback: function(options, success, response) {
                                                                                                    mask.hide();
                                                                                                    var reader = new Ext.slims.XmlReader();
                                                                                                    var data = reader.read(response);
                                                                                                    if(data.success) {
                                                                                                        Ext.StoreMgr.get('users-departments-store').reload({
                                                                                                            callback: function() {
                                                                                                                                                                                                                              
                                                                                                                Ext.Msg.show({
                                                                                                                    title: 'Add Department: Success',
                                                                                                                    msg: 'Record added successfully',
                                                                                                                    icon: Ext.MessageBox.INFO,
                                                                                                                    buttons: Ext.Msg.OK,
                                                                                                                    fn: function() {
                                                                                                                        Ext.getCmp('users-departments-form-textbox').reset();
                                                                                                                    }
                                                                                                                });
                                                                                                                
                                                                                                            }
                                                                                                        });
                                                                                                    } else {
                                                                                                        
                                                                                                        Ext.Msg.show({
                                                                                                            title: 'Add Department: Fail',
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
                                                                                            id: 'users-departments-form-list',
                                                                                            fieldLabel: 'Groups',
                                                                                            xtype: 'listview',
                                                                                            autoScroll: true,
                                                                                            height: 300,
                                                                                            store: Ext.StoreMgr.get('users-departments-store'),
                                                                                            columns: [{
                                                                                                header: 'Name',
                                                                                                dataIndex: 'Department'
                                                                                            }]
                                                                                        },{
                                                                                            id: 'users-departments-form-textbox',
                                                                                            xtype: 'textfield',
                                                                                            fieldLabel: 'Department Name',
                                                                                            minLength: 1,
                                                                                            maxLength: 255
                                                                                        }]
                                                                                        
                                                                                    }]
                                                                                });
                                                                            window.deptwin.show();
                                                                        }
                                                                        var s = Ext.StoreMgr.get('users-departments-store');
                                                                        if(s.getCount() === 0)
                                                                            s.load({
                                                                                callback: showDeptWindow 
                                                                            });
                                                                        else
                                                                            showDeptWindow();
                                                                    }
                                                                }]
                                                        },{
                                                            id: 'users-groups-form-isadmin-checkbox',
                                                            xtype: 'checkbox',
                                                            fieldLabel: 'Is admin'
                                                        }]
                                                    }] 
                                                });
                                            window.groupwin.show();
                                        }
                                        var s = Ext.StoreMgr.get('users-groups-store');
                                        if(s.getCount() === 0)
                                            s.load({
                                                callback: showWindow 
                                            });
                                        else
                                            showWindow();
                                    }
                                }]
                            }]
                        },{
                            id: 'users-form-editor-grid',
                            xtype: 'grid',
                            region: 'center',
                            autoExpandColumn: 'user-name',
                            tbar: [{
                                text: 'Update Selection',
                                handler: function() {
                                    var id = Ext.getCmp('users-form-id').getValue();
                                    if(typeof id === 'undefined' || id.length === 0)
                                        return;
                                    var name = Ext.getCmp('users-form-name').getValue();
                                    if(typeof name === 'undefined' || name.length === 0)
                                        return;
                                    var groupid = Ext.getCmp('users-groups-combo').getValue();
                                    if(typeof groupid === 'undefined' || groupid == null || (groupid + '').length === 0)
                                        return;
                                    var mask = new Ext.LoadMask(Ext.getBody(), {
                                        msg: 'Saving...'
                                    });
                                            
                                    mask.show();
                                                 
                                    Ext.Ajax.request({
                                        url: 'queries/add-user.php',
                                        params: {
                                            Id: id,
                                            Staff: name,
                                            ResearchGroup: groupid
                                        },
                                        method: 'post',
                                        callback: function(options, success, response) {
                                            mask.hide();
                                            var reader = new Ext.slims.XmlReader();
                                            var data = reader.read(response);
                                            if(data.success) {
                                                Ext.StoreMgr.get('group-store').reload({
                                                    callback: function() {
                                                        
                                                        
                                                        Ext.Msg.show({
                                                            title: 'Add User: Success',
                                                            msg: 'Record added successfully',
                                                            icon: Ext.MessageBox.INFO,
                                                            buttons: Ext.Msg.OK,
                                                            fn: function() {
                                                                Ext.getCmp('users-form-id').reset();
                                                                Ext.getCmp('users-form-name').reset();
                                                                Ext.getCmp('users-groups-combo').reset();
                                                            }
                                                        });
                                                        
                                                    }
                                                });
                                            } else {
                                                
                                                Ext.Msg.show({
                                                    title: 'Add User: Fail',
                                                    msg: 'Error adding records. No records added<br/>' + (function() { var s; for(var i = 0, s = ''; i < data.records.length; s += '<br/>' + data.records[i].data.message, i++); return s; })(),
                                                    icon: Ext.MessageBox.ERROR,
                                                    buttons: Ext.Msg.OK
                                                });
                                            }
                                        }
                                    });
                                }
                            },{
                                xtype: 'tbseparator'
                            },{
                                id: 'users-form-remove-user-button',
                                text: 'Remove Selected Row(s)',
                                disabled: true,
                                handler: function(){
                                    var grid = Ext.getCmp('users-form-editor-grid');
                                    var model = grid.getSelectionModel();
                                    if(model && model.getCount() !== 0) {
                                        var records = model.getSelections();
                                        var ids = [];
                                        for(var i = 0, count = records.length; i < count; i++) {
                                            ids.push(records[i].data.ID);
                                        }
                                        if(ids.length == 0)
                                            return;
                                        var mask = new Ext.LoadMask(Ext.getBody(), {
                                            msg: 'Saving...'
                                        });
                                                
                                        mask.show();
                                         
                                        Ext.Ajax.request({
                                            url: 'queries/remove-user.php',
                                            params: {
                                                Id: ids.join('+')
                                            },
                                            method: 'post',
                                            callback: function(options, success, response) {
                                                mask.hide();
                                                var reader = new Ext.slims.XmlReader();
                                                var data = reader.read(response);
                                                if(data.success) {
                                                    Ext.StoreMgr.get('group-store').reload({
                                                        callback: function() {
                                                            
                                                            Ext.Msg.show({
                                                                title: 'Remove User: Success',
                                                                msg: 'Record removed successfully',
                                                                icon: Ext.MessageBox.INFO,
                                                                buttons: Ext.Msg.OK
                                                            });
                                                            
                                                        }
                                                    });
                                                } else {
                                                    
                                                    Ext.Msg.show({
                                                        title: 'Remove User: Fail',
                                                        msg: 'Error removing records. No records removed<br/>' + (function() { var s; for(var i = 0, s = ''; i < data.records.length; s += '<br/>' + data.records[i].data.message, i++); return s; })(),
                                                        icon: Ext.MessageBox.ERROR,
                                                        buttons: Ext.Msg.OK
                                                    });
                                                }
                                            }
                                        });
                                    }
                                }
                            }],
                            store: new Ext.data.XmlStore({
                                storeId: 'group-store',
                                fields: ['ID', 'Staff', 'ResearchGroup', 'Department', { name: 'IsAdmin', type: 'bool'}],
                                record: 'row',
                                url: 'queries/get-users-xml.php',
                                sortInfo: {
                                    field: 'Staff',
                                    direction: 'ASC'
                                }
                            }),
                            columns: [{
                                id: 'user-name',
                                header: 'Name',
                                dataIndex: 'Staff'
                            },{
                                header: 'Group',
                                dataIndex: 'ResearchGroup'
                            },{
                                header: 'Department',
                                dataIndex: 'Department'
                            },{
                                header: 'Is Admin',
                                dataIndex: 'IsAdmin',
                                renderer: function(value) {
                                    return (!!value) ? 'Yes' : 'No';
                                }
                            }],
                            listeners: {
                                render: {
                                    fn: function(grid) {
                                        var mask = new Ext.LoadMask(grid.getEl(), {
                                            msg: 'Loading...'
                                        });
                                        mask.show();
                                        grid.getStore().load({
                                            callback: function(r, options, success) {
                                                mask.hide();
                                                // add stores to the other controls
                                            }
                                        });
                                    }
                                },
                                added: {
                                    fn: function(grid) {
                                        var model = grid.getSelectionModel();
                                        model.addListener('selectionchange', function() {
                                            var button = Ext.getCmp('users-form-remove-user-button');
                                            button.setDisabled(model.getCount() == 0);
                                        });
                                        
                                    }
                                }
                                
                            }
                        }]
                    }<?php } else { ?>
                    ,{
                        id: 'users-tab',
                        title: 'Users',
                        disabled: true
                    }<?php } ?>
                    ,<?php if($isadmin) { ?>
                    {
                        id: 'dewars-tab',
                        title: 'Dewars',
                        layout: 'border',
                        items: [{
                            id: 'dewar-entry-form',
                            layout: 'form',
                            region: 'west',
                            width: 350,
                            labelWidth: 100,
                            bodyStyle: 'padding: 5px;',
                            defaults: { width: 215 },
                            items: [{
                                id: 'dewar-definition-name',
                                xtype: 'textfield',
                                fieldLabel: 'Dewar Name'
                            }]
                        
                        }, {
                            xtype: 'grid',
                            layout: 'fit',
                            region: 'center',
                            tbar: [
                                {
                                    text: 'Update Selection',
                                    handler: function() {
                                        var name = Ext.getCmp('dewar-definition-name').getValue();
                                        
                                        Ext.Ajax.request({
                                            method:'post',
                                            url: 'queries/add-dewar.php',
                                            params: {
                                                dewarname: name
                                            },
                                            callback: function() {
                                                var store = Ext.StoreMgr.get('dewar-definition-store');
                                                store.reload();
                                            }
                                        })
                                    }
                                }
                            ],
                            store: {
                                storeId: 'dewar-definition-store',
                                xtype: 'xmlstore',
                                record: 'row',
                                url: 'queries/get-dewar-definition.php',
                                fields: [
                                    'DewarName'
                                ],
                                autoLoad: true
                            },
                            columns: [
                                {
                                    header: 'Dewar Name',
                                    dataIndex: 'DewarName'
                                }
                            ]
                        }]
                    }
                    <?php } ?>
                    <?php if($isadmin) { ?>
                    ,{
                        id: 'group-stacks-tab',
                        layout: 'border',
                        title: 'Stacks',
                        //height: 668,
                        items: [{
                            id: 'groups-stack-filter-form',
                            layout: 'form',
                            region: 'west',
                            width: 350,
                            labelWidth: 100,
                            bodyStyle: 'padding: 5px;',
                            defaults: { width: 215 },
                            updateFilter: function() {
                                var stackcombo = Ext.getCmp('group-stacks-stack');
                                var stack = stackcombo.getValue();
                                var dewarcombo = Ext.getCmp('group-stacks-dewar');
                                var dewar = dewarcombo.getValue();
                                var stackonlycheck = Ext.getCmp('group-stack-stack-only-checkbox');
                                var stackonly = stackonlycheck.getValue();
                                var groupstackstore = Ext.StoreMgr.get('group-stack-store');
                                
                                var f = [];
                                if(dewar !== '')
                                    f.push({
                                        property: 'Dewar',
                                        value: dewar,
                                        exactMatch: true
                                    });
                                if(stack !== '')
                                    f.push({
                                        property: 'Stack',
                                        value: stack,
                                        exactMatch: true
                                    });
                                if(stackonly)
                                    f.push({
                                        property: 'IsUnassigned',
                                        value: true,
                                        exactMatch: true
                                    });
                                
                                groupstackstore.filter(f);
                                
                            },
                            tbar: [{
                                text: 'Refresh',
                                handler: function() {
                                    var dewarcombo = Ext.getCmp('group-stacks-dewar');
                                    dewarcombo.clearValue();
                                    var stackcombo = Ext.getCmp('group-stacks-stack');
                                    stackcombo.clearValue();
                                    var stackstore = Ext.StoreMgr.get('group-stack-stack-store');
                                    stackstore.removeAll();
                                    var stackonly = Ext.getCmp('group-stack-stack-only-checkbox');
                                    stackonly.setValue(false); // unchecked
                                    Ext.getCmp('groups-stack-filter-form').updateFilter();
                                    Ext.StoreMgr.get('group-stack-store').reload();
                                }
                            }],
                            items: [{
                                id: 'group-stacks-dewar',
                                xtype: 'combo',
                                fieldLabel: 'Dewar',
                                mode: 'local',
                                forceSelection: true,
                                typeAhead: false,
                                editable: false,
                                triggerAction: 'all',
                                emptyText: 'Select a dewar',
                                store: {
                                    id: 'group-stack-dewar-store',
                                    xtype: 'arraystore',
                                    fields: ['Dewar']
                                },
                                valueField: 'Dewar',
                                displayField: 'Dewar',
                                autoLoad: false
                                , listeners: {
                                    select: {
                                        fn: function(component, record, index) {
                                            var stackcombo = Ext.getCmp('group-stacks-stack');
                                            stackcombo.clearValue();
                                            var groupstackstore = Ext.StoreMgr.get('group-stack-store');
                                            var collection = groupstackstore.query('Dewar', record.get('Dewar'), false);
                                            var stackstore = Ext.StoreMgr.get('group-stack-stack-store');
                                            stackstore.removeAll();
                                            var records = [], stacks = [];
                                            for(var i = 0, length = collection.getCount(); i < length; i++) {
                                                var item = collection.get(i);
                                                var dewar = item.data.Dewar;
                                                var stack = item.data.Stack;
                                                if(stacks.indexOf(stack) === -1) {
                                                    stacks.push(stack);
                                                    var record = new stackstore.recordType({ Dewar: dewar, Stack:  stack}, dewar + '-' + stack);
                                                    stackstore.add([record]);
                                                }
                                            }
                                            Ext.getCmp('groups-stack-filter-form').updateFilter();
                                        }
                                    }
                                }
                            }, {
                                xtype: 'panel',
                                layout: 'hbox',
                                fieldLabel: 'Stack',
                                frame: false,
                                border: false,
                                items: [{
                                    id: 'group-stacks-stack',
                                    xtype: 'combo',
                                    
                                    mode: 'local',
                                    forceSelection: true,
                                    typeAhead: false,
                                    editable: false,
                                    triggerAction: 'all',
                                    emptyText: 'Select a stack',
                                    store: {
                                        storeId: 'group-stack-stack-store',
                                        xtype: 'arraystore',
                                        fields: ['Dewar','Stack']
                                    },
                                    valueField: 'Stack',
                                    displayField: 'Stack',
                                    autoLoad: false,
                                    listeners: {
                                        select: {
                                            fn: function(component, record, index) {
                                                Ext.getCmp('groups-stack-filter-form').updateFilter();
                                                return true;
                                            }
                                        }
                                    }
                                }, {
                                    xtype: 'button',
                                    text: 'New',
                                    handler: function() {
                                        var dewarname = Ext.getCmp('group-stacks-dewar').getValue();
                                        if(!dewarname) {
                                            return false;
                                        }
                                        var grid = Ext.getCmp('group-stack-grid');
                                        var stackmask = new Ext.LoadMask(grid.getEl(), {
                                            msg: 'Saving...',
                                            removeMask: true
                                        });
                                        stackmask.show();
                                        Ext.Ajax.request({
                                           method: 'post',
                                           url: 'queries/add-stack.php',
                                           params: {
                                                dewarname: dewarname
                                           },
                                           callback: function() {
                                                var store = grid.getStore()
                                                store.on('load', function(s, records, options) {
                                                    var record = null,
                                                        i = -1,
                                                        index;
                                                    do {
                                                        index = i;
                                                        i = s.find('Dewar', dewarname, i + 1, false, false);
                                                    } while(i !== -1);
                                                    if(index === -1) {
                                                        stackmask.hide();
                                                        return;
                                                    }
                                                    var grid = Ext.getCmp('group-stack-grid');
                                                    var sm = grid.getSelectionModel();
                                                    record = s.getAt(index);
                                                    sm.selectRecords([record]);
                                                    stackmask.hide();
                                                });
                                                store.reload();
                                                
                                           }
                                        });
                                    }
                                }]
                            
                            },{
                                id: 'group-stack-stack-only-checkbox',
                                xtype: 'checkbox',
                                boxLabel: 'Unassigned stacks only',
                                listeners: {
                                    check: {
                                        fn: function(box, checked) {
                                            Ext.getCmp('groups-stack-filter-form').updateFilter();
                                        }
                                    }
                                }
                            }, {
                                id: 'group-stack-assignment-radio-group',
                                xtype: 'radiogroup',
                                columns: 1,
                                items: [{
                                    id: 'group-stack-assignment-unassign-radio',
                                    name: 'assignment',
                                    xtype: 'radio',
                                    boxLabel:'Unassign',
                                    value: 'unassign'
                                },{
                                    id: 'group-stack-assignment-assign-radio',
                                    name: 'assignment',
                                    xtype: 'radio',
                                    checked: true,
                                    boxLabel: 'Assign to group',
                                    value: 'assign',
                                    listeners: {
                                        check: {
                                            fn: function(box, checked) {
                                                var combo = Ext.getCmp('group-stack-group-combo');
                                                combo.setDisabled(!checked);
                                            }
                                        }
                                    }
                                }]
                            },
                            {
                                id: 'group-stack-group-combo',
                                xtype: 'combo',
                                fieldLabel: 'Research Group',
                                mode: 'local',
                                forceSelection: true,
                                typeAhead: false,
                                editable: false,
                                triggerAction: 'all',
                                store: Ext.StoreMgr.get('users-groups-store'),
                                valueField: 'ResearchGroupID',
                                displayField: 'ResearchGroup',
                                emptyText: 'Select a group',
                                listeners: {
                                    added: {
                                        fn: function(component, owner, index) {
                                            var store = component.getStore();
                                            if(store && store.getCount() === 0)
                                                store.load();
                                        }
                                    }
                                },
                                tpl: new Ext.XTemplate('<tpl for=".">',
                                                            '<div class="x-combo-list-item" ext:qtip="{ResearchGroup} - {Department}">',
                                                                '<span>',
                                                                    '{ResearchGroup}',
                                                                '</span>',
                                                                '&nbsp;',
                                                                '<span style="color:gray">',
                                                                    '{Department}',
                                                                '</span>',
                                                            '</div>',
                                                        '</tpl>')
                                
                            }]
                        },{
                            id: 'group-stack-grid',
                            xtype: 'grid',
                            region: 'center',
                            sm: new Ext.grid.RowSelectionModel({ singleSelect: true }),
                            listeners: {
                                render: {
                                    fn: function(grid) {
                                        Ext.StoreMgr.get('group-stack-store').load();
                                    }       
                                },
                                added: {
                                    fn: function(grid, owner, index) {
                                        var model = grid.getSelectionModel();
                                        model.addListener('selectionchange', function(model) {
                                            var button = Ext.getCmp('group-stack-toolbar-update-button');
                                            var hasselections = (model.getCount() !== 0);
                                            button.setDisabled(!hasselections);
                                            button = Ext.getCmp('group-stack-toolbar-clear-button');
                                            button.setDisabled(!hasselections);
                                        });
                                    }
                                }
                            },
                            loadMask: {
                                msg: 'Loading...'
                            },
                            tbar: [{
                                id: 'group-stack-toolbar-update-button',
                                text: 'Update Selection',
                                disabled: true,
                                listeners: {
                                    click: {
                                        fn: function() {
                                            var grid = Ext.getCmp('group-stack-grid');
                                            var model = grid.getSelectionModel();
                                            var groupid;
                                            var radiogroup = Ext.getCmp('group-stack-assignment-radio-group');
                                            var radio = radiogroup.getValue();
                                            if(radio.getId() === 'group-stack-assignment-assign-radio') {
                                                groupid = Ext.getCmp('group-stack-group-combo').getValue();
                                                if(typeof groupid === 'undefined' || (groupid + '').length === 0)
                                                    return;
                                            } else { // we're unassigning - query expects group id of 0 for unassignment
                                                groupid = 0;
                                            }
                                            model.each(function(record){
                                                var dewar = record.get('Dewar');
                                                var stack = record.get('Stack');
                                                var group = record.get('ResearchGroupID');
                                                
                                                var request = function() {
                                                    if(dewar && stack) {
                                                        var mask = new Ext.LoadMask(Ext.getBody(), {
                                                            msg: 'Saving...'
                                                        });
                                                                
                                                        mask.show();
                                                                                          
                                                        Ext.Ajax.request({
                                                            url: 'queries/set-stack-group.php',
                                                            params: {
                                                                Dewar: dewar,
                                                                Stack: stack,
                                                                ResearchGroupID: groupid
                                                            },
                                                            method: 'post',
                                                            callback: function(options, success, response) {
                                                                mask.hide();
                                                                var reader = new Ext.slims.XmlReader();
                                                                var data = reader.read(response);
                                                                if(data.success) {
                                                                    Ext.StoreMgr.get('group-stack-store').reload({
                                                                        callback: function() {
                                                                            Ext.Msg.show({
                                                                                title: 'Set Stack Owner: Success',
                                                                                msg: 'Record set successfully',
                                                                                icon: Ext.MessageBox.INFO,
                                                                                buttons: Ext.Msg.OK
                                                                            });
                                                                        }
                                                                    });
                                                                } else {
                                                                    Ext.Msg.show({
                                                                        title: 'Set Stack Owner: Fail',
                                                                        msg: 'Error updating records. No records updated<br/>' + (function() { var s; for(var i = 0, s = ''; i < data.records.length; s += '<br/>' + data.records[i].data.message, i++); return s; })(),
                                                                        icon: Ext.MessageBox.ERROR,
                                                                        buttons: Ext.Msg.OK
                                                                    });
                                                                }
                                                            }
                                                        });
                                                    }
                                                }
                                                if(group) {
                                                   Ext.Msg.confirm('Confirm Reassignment', 'This stack is already assigned. Ok to ' + (groupid? 're': 'un') + 'assign?', function(button) {
                                                        if(button === 'yes') {
                                                            request();
                                                        }
                                                   });
                                                } else {
                                                    request();
                                                }
                                                
                                            });
                                        }
                                    }
                                }
                            }, {
                                id: 'group-stack-toolbar-clear-button',
                                text: 'Clear Selection',
                                disabled: true,
                                listeners: {
                                    click: {
                                        fn: function() {
                                            var grid = Ext.getCmp('group-stack-grid');
                                            var model = grid.getSelectionModel();
                                            var waslocked = model.isLocked();
                                            if(waslocked)
                                                model.unlock();
                                            model.clearSelections(false);
                                            if(waslocked)
                                                model.lock();
                                        }
                                    }
                                }
                            }],
                            store: new Ext.data.XmlStore({
                                storeId: 'group-stack-store',                    
                                url: 'queries/get-group-stacks.php',
                                fields: ['Dewar', 'Stack', 'ResearchGroupID', 'ResearchGroup', 'Department', { name: 'IsUnassigned', type: 'boolean' }],
                                record: 'row',
                                sortInfo: {
                                    field: 'Dewar',
                                    direction: 'ASC'
                                },
                                autoLoad: false,
                                
                                listeners: {
                                    
                                    load: {
                                        fn: function() {
                                            var combo = Ext.getCmp('group-stacks-dewar');
                                            var value = combo.getValue();
                                            var store = combo.getStore();
                                            store.removeAll();
                                            
                                            var dewars = Ext.StoreMgr.get('group-stack-store').collect('Dewar');
                                            var records = [];
                                            var current;
                                            for(var i = 0, length = dewars.length; i < length; i++) {
                                                var dewar = { Dewar: dewars[i] };
                                                var record = new store.recordType(dewar, dewar.Dewar);
                                                if(value && dewar.Dewar == value)
                                                    current = record;
                                                records.push(record);
                                            }
                                            store.add(records);
                                            if(current) {
                                                combo.setValue(value);
                                                combo.fireEvent('select', combo, current);                        
                                            }
                                        }
                                    }
                                }
                            }),
                            columns: [{
                                header: 'Dewar',
                                datapath: 'Dewar'
                            },{
                                header: 'Stack',
                                datapath: 'Stack',
                                renderer: function(value) {
                                    return (!!value) ? value : '<i style="color: gray;">&lt;none&gt;</i>';
                                }
                            },{
                                header: 'Research Group ID',
                                datapath: 'ResearchGroupID',
                                hidden: true,
                                renderer: function(value) {
                                    return (!!value) ? value : '<i style="color: gray;">&lt;none&gt;</i>';
                                }
                            },{
                                header: 'Research Group',
                                datapath: 'ResearchGroup',
                                renderer: function(value) {
                                    return (!!value) ? value : '<i style="color: gray;">&lt;none&gt;</i>';
                                }
                            },{
                                header: 'Department',
                                datapath: 'Department',
                                renderer: function(value) {
                                    return (!!value) ? value : '<i style="color: gray;">&lt;none&gt;</i>';
                                }
                            }]
                        }] 
                    }
                    <?php } else { ?>
                    ,{
                        title: 'Stacks',
                        disabled: true
                    }
                    <?php } ?>
                    <?php if($isadmin) { ?>
                    , {
                        id: 'boxes-tab',
                        title: 'Boxes',
                        xtype: 'panel',
                        layout: 'border',
                        items: [{
                            id: 'boxes-box-form',
                            region: 'west',
                            xtype: 'form',
                            //layout: 'form',
                            width: 350,
                            labelWidth: 100,
                            bodyStyle: 'padding: 5px;',
                            defaults: { width: 215 },
                            updateFilter: function() {
                                var stackcombo = Ext.getCmp('boxes-stack-combo');
                                var stack = stackcombo.getValue();
                                var dewarcombo = Ext.getCmp('boxes-dewar-combo');
                                var dewar = dewarcombo.getValue();
                                var boxstore = Ext.StoreMgr.get('boxes-box-grid-store');
                                
                                var f = [];
                                if(dewar !== '')
                                    f.push({
                                        property: 'Dewar',
                                        value: dewar,
                                        exactMatch: true
                                    });
                                if(stack !== '')
                                    f.push({
                                        property: 'Stack',
                                        value: stack,
                                        exactMatch: true
                                    });
                                                               
                                boxstore.filter(f);
                                
                            },
                            tbar: [{
                                text: 'Refresh',
                                handler: function() {
                                    var stackcombo = Ext.getCmp('boxes-stack-combo');
                                    var stack = stackcombo.clearValue();
                                    var dewarcombo = Ext.getCmp('boxes-dewar-combo');
                                    var dewar = dewarcombo.clearValue();
                                    Ext.getCmp('boxes-box-form').updateFilter();
                                    Ext.StoreMgr.get('boxes-box-grid-store').reload();
                                }
                            }],
                            items: [{
                                id: 'boxes-dewar-combo',
                                xtype: 'combo',
                                fieldLabel: 'Dewar',
                                emptyText: 'Choose dewar',
                                mode: 'local',
                                store: {
                                    storeId: 'boxes-dewar-store',
                                    xtype: 'arraystore',
                                    idProperty: 'Dewar',
                                    fields: ['Dewar'],
                                    autoLoad: false,
                                    sortInfo: {
                                        field: 'Dewar',
                                        direction: 'ASC'
                                    }
                                },
                                displayField: 'Dewar',
                                valueField: 'Dewar',
                                triggerAction: 'all',
                                listeners: {
                                    select: function(combo, record, index) {
                                        var dewar = record.get('Dewar');
                                        if(!dewar)
                                            return false;
                                        var store = Ext.StoreMgr.get('boxes-box-grid-store');
                                        var records = store.query('Dewar', dewar, false);
                                        var stackstore = Ext.StoreMgr.get('boxes-stack-store');
                                        stackstore.removeAll();
                                        var recs = [];
                                        records.each(function(item, index, items) {
                                            var s = item.get('Stack');
                                            recs.push(new stackstore.recordType({ Stack: s }, s));
                                        });
                                        stackstore.add(recs);
                                        Ext.getCmp('boxes-box-form').updateFilter();
                                        stackstore.sort('Stack', 'ASC');
                                    }
                                }
                            }, {
                                id: 'boxes-stack-combo',
                                xtype: 'combo',
                                fieldLabel: 'Stack',
                                emptyText: 'Choose stack',
                                mode: 'local',
                                store: {
                                    storeId: 'boxes-stack-store',
                                    xtype: 'arraystore',
                                    idProperty: 'Stack',
                                    fields: ['Stack'],
                                    autoLoad: false,
                                    sortInfo: {
                                        field: 'Stack',
                                        direction: 'ASC'
                                    }
                                },
                                displayField: 'Stack',
                                triggerAction: 'all',
                                listeners: {
                                    select: function() {
                                        Ext.getCmp('boxes-box-form').updateFilter();
                                    }
                                }
                            }, {
                                id: 'boxes-comment-area',
                                xtype: 'textarea',
                                fieldLabel: 'Comment',
                                maxLength: 255,
                                emptyText: 'Enter box comment (optional)'
                            }, {
                                xtype: 'panel',
                                layout: 'hbox',
                                border: false,
                                frame: false,
                                fieldLabel: 'Box Dimensions (positions, w x h)',
                                items: [{
                                    id: 'boxes-width-number',
                                    xtype: 'textfield',
                                    width: '3em',
                                    value: 10,
                                    maxValue: 10
                                }, { xtype: 'label', text: 'x', style: { padding: '5px' } }, {
                                    id: 'boxes-height-number',
                                    xtype: 'numberfield',
                                    width: '3em',
                                    value: 10,
                                    maxValue: 10
                                }]
                            }, {
                                xtype: 'numberfield',
                                id: 'boxes-box-count',
                                fieldLabel: 'No. of Boxes',
                                value: 1,
                                minValue: 1,
                                maxValue: 99,
                                width: '3em'
                            }]
                        }, {
                            id: 'add-box-grid',
                            xtype: 'grid',
                            layout: 'fit',
                            region: 'center',
			    plugins: [editor],
                            tbar: [
                                {
                                    text: 'Update Selection',
                                    handler: function() {
                                        var dewar = Ext.getCmp('boxes-dewar-combo').getValue();
                                        if(!dewar)
                                            return false;
                                        var stack = Ext.getCmp('boxes-stack-combo').getValue();
                                        if(!stack)
                                            return false;
                                        var invalids = [];
                                        var width = Ext.getCmp('boxes-width-number').getValue();
                                        if(width <= 0 || width > Ext.getCmp('boxes-width-number').maxValue) {
                                            var form = Ext.getCmp('boxes-box-form').getForm();
                                            invalids.push({id:'boxes-width-number', message: 'width must be greater than 0, no greater than 10'});
                                            //return false;
                                        }
                                        var height = Ext.getCmp('boxes-height-number').getValue();
                                        if(height <= 0 || height > Ext.getCmp('boxes-height-number').maxValue) {
                                            var form = Ext.getCmp('boxes-box-form').getForm();
                                            invalids.push({id:'boxes-height-number', message: 'height must be greater than 0, no greater than 10'});
                                            //return false;
                                        }
                                        var count = Ext.getCmp('boxes-box-count').getValue();
                                        if(count < 1 || count > Ext.getCmp('boxes-box-count').maxValue) {
                                            invalids.push({id:'boxes-box-count', message: 'number of boxes must be greater than 0, no greater than 99'});
                                        }
                                        if(invalids.length !== 0) {
                                            var form = Ext.getCmp('boxes-box-form').getForm();
                                            form.markInvalid(invalids);
                                            return false;
                                        }
                                        
                                            
                                        var comment = Ext.getCmp('boxes-comment-area').getValue();
                                        Ext.Ajax.request({
                                            method: 'post',
                                            url: 'queries/add-box.php',
                                            params: {
                                                Dewar: dewar,
                                                Stack: stack,
                                                Comment: comment,
                                                Width: width,
                                                Height: height,
                                                Boxcount: count
                                            },
                                            callback: function() {
                                                var store = Ext.StoreMgr.get('boxes-box-grid-store');
                                                store.on('load', function(s) {
                                                    var record = null,
                                                        i = -1,
                                                        index;
                                                    do {
                                                        index = i;
                                                        i = s.findBy(function(record, id) {
                                                            return (record.get('Dewar') == dewar && record.get('Stack') == stack);
                                                        }, s, i + 1);
                                                    } while(i !== -1);
                                                    if(index === -1) {
                                                        return;
                                                    }
                                                    var grid = Ext.getCmp('add-box-grid');
                                                    var sm = grid.getSelectionModel();
                                                    record = s.getAt(index);
                                                    sm.selectRecords([record]);
                                                    Ext.getCmp('boxes-box-form').updateFilter();
                                                });
                                                store.reload();
                                                
                                            }
                                        });
                                        
                                        
                                    }
                                }
                            ],
                            store: {
                                    xtype: 'xmlstore',
                                    storeId: 'boxes-box-grid-store',
                                    url: 'queries/get-boxes.php',
                                    record: 'row',
                                    fields: ['Dewar', 'Stack', 'Box', {name: 'Width', type: 'int' }, {name: 'Height', type: 'int' }, 'Comment'],
                                    loadMask: {
                                        msg: 'Loading...'  
                                    },
                                    autoLoad: false
                                },
                            columns: [{
                                header: 'Dewar',
                                dataIndex: 'Dewar',
                                sortable: true
                            }, {
                                header: 'Stack',
                                dataIndex: 'Stack',
                                renderer: function(value) {
                                    if(typeof value === "undefined" || value === null || (value + "").length === 0)
                                        return '<i style="color: gray;">&lt;none&gt;</i>';
                                    return value;
                                }
                            }, {
                                header: 'Box',
                                dataIndex: 'Box',
                                renderer: function(value) {
                                    if(typeof value === "undefined" || value === null || (value + "").length === 0)
                                        return '<i style="color: gray;">&lt;none&gt;</i>';
                                    return value;
                                }
                            }, {
                                xtype: 'numbercolumn',
                                header: 'Box Width (Positions)',
                                dataIndex: 'Width'
                                , format: '0',
				editor: {
					xtype: 'numberfield'
				}
                            }, {
                                xtype: 'numbercolumn',
                                header: 'Box Height (Positions)',
                                dataIndex: 'Height'
                                , format: '0',
				editor: {
					xtype: 'numberfield'
				}
                            }, {
                                header: 'Comment',
                                dataIndex: 'Comment',
				editor: {
					xtype: 'textfield'
				}
                            }]
                        }],
                        listeners: {
                            activate: function() {
                                var store = Ext.StoreMgr.get('boxes-box-grid-store');
                                if(store.getCount() != 0)
                                    return true;
                                store.load({
                                    callback: function() {
                                        var dewarstore = Ext.getCmp('boxes-dewar-combo').getStore();
                                        dewarstore.removeAll();
                                        var dewars = store.collect('Dewar');                                                                             
                                        var records = [];
                                        for(var i = 0, r = dewars.length; i < r; i++) {
                                            records.push(new dewarstore.recordType({ Dewar: dewars[i] }, dewars[i]));
                                        }
                                        dewarstore.add(records);
                                    }
                                });
                            }
                        }
                    }
                    <?php } else { ?>
                    ,{
                        title: 'Boxes',
                        disabled: true
                    }
                    <?php } ?>
                    <?php if($isadmin) { ?>
                    ,{
                        id: 'history-tab',
                        title: 'History',
                        xtype: 'grid',
                        mode: 'remote',
                        listeners: {
                            activate: {
                                fn: function(panel) {
                                    var store = panel.getStore();
                                    
                                    if(store.getCount() === 0) {
                                        var mask = new Ext.LoadMask(panel.getEl(), {
                                            msg: 'Loading...'
                                        });
                                        mask.show();
                                        store.load({
                                            params: {
                                                start: 0,
                                                limit: 30
                                            },
                                            callback: function() {
                                                mask.hide();
                                            }
                                        });
                                    }
                                }
                            }
                        },
                        store: new Ext.data.XmlStore({
                            storeId: 'history-store',
                            url: 'queries/get-history-xml.php',
                            fields: [
                                'ID',
                                'Dewar',
                                'Stack',
                                'Box',
                                'Position',
                                'CellLine',
                                { name:'ActivityDate', type: 'date', dateFormat: 'd/m/Y' },
                                'Operation',
                                'Staff',
                                'ResearchGroup',
                                {name: 'RecordAddedDate', type: 'date', dateFormat: 'd/m/Y H:i:s'},
                                'User'
                            ],
                            remoteSort: true,
                            record: 'row',
                            successProperty: '@success',
                            totalProperty: '@total',
                            sortInfo: {
                                field: 'RecordAddedDate',
                                direction: 'DESC'
                            }
                        }),
                        bbar: {
                            xtype: 'paging',
                            store: Ext.StoreMgr.get('history-store'),
                            displayInfo: true,
                            pageSize: 30
                        },
                        viewConfig: {
                            autoFill: true  
                        },
                        columns: [{
                            header: 'ID',
                            hidden: true,
                            dataIndex: 'ID',
                            sortable: true
                        },{
                            header: 'Dewar',
                            dataIndex: 'Dewar',
                            sortable: true
                        },{
                            header: 'Stack',
                            dataIndex: 'Box',
                            sortable: true
                        },{
                            header: 'Box',
                            dataIndex: 'Box',
                            sortable: true
                        },{
                            header: 'Position',
                            dataIndex: 'Position',
                            sortable: true
                        },{
                            id: 'history-cellline-column',
                            header: 'Cell Line',
                            dataIndex: 'CellLine',
                            sortable: true
                        },{
                            header: 'Activity Date',
                            dataIndex: 'ActivityDate',
                            xtype: 'datecolumn',
                            format: 'd/m/Y',
                            sortable: true
                        },{
                            header: 'Activity',
                            dataIndex: 'Operation',
                            sortable: true
                        },{
                            header: 'Staff / Owner',
                            dataIndex: 'Staff',
                            sortable: true
                        },{
                            header: 'Recorded',
                            dataIndex: 'RecordAddedDate',
                            xtype: 'datecolumn',
                            format: 'd/m/Y H:i:s',
                            sortable: true
                        },{
                            header: 'Slims User',
                            dataIndex: 'User',
                            hidden: true,
                            sortable: true
                        }]
                    }
                    <?php } else { ?>
                    ,{
                        id: 'history-tab',
                        title: 'History',
                        disabled: true
                    }
                    <?php }
                    } else { // not valid user ?>
                    {
                        title: 'Add Sample',
                        disabled: true
                    },{
                        title: 'New Cell Line',
                        disabled: true
                    },{
                        title: 'Remove Sample',
                        disabled: true
                    },{
                        title: 'Users',
                        disabled: true
                    },{
                        title: 'Dewars',
                        disabled: true
                    }, {
                        title: 'Stacks',
                        disabled: true
                    },{
                        title: 'History',
                        disabled: true
                    }
                    <?php }?>
                    ]
                }]
            });
            
            setTimeout(function(){
                Ext.get('loading').remove();
                Ext.get('loading-mask').fadeOut({
                    remove:true
                });
            }, 4000);
        });
    </script>
    </head>
    <body>
        <img id="slimslogo" src="images/nerd_glasses.png"/><h1 id="header">SLIMS</h1>
        <div id="mainpanel"/>
        <div id="loading-mask"/>
        <div id="loading"> 
            <div class="loading-indicator"> 
                Loading&hellip;
            </div> 
        </div>     
    </body>
</html>
