Ext.define('Slims.view.sample.wizard.PositionsView', {
    extend: 'Ext.panel.Panel',
    xtype: 'positionsview',

    layout: 'vbox',
    data: [],

    initComponent: function() {
        this.buildItems();
        this.callParent(arguments);
    },

    buildItems: function() {
        var colsItems = [],
            data = this.data;
        for (var i in data) {
            var colData = data[i];
            colsItems.push(this.createRowPanel(colData));
        }
        this.items = colsItems;
    },

    createRowPanel: function(data) {
        var items = [];
        for (var i in data) {
            var conf = data[i] || {};
            var cb = Ext.create('Ext.form.field.Checkbox',  {
                hideLabel: true,
                checked: !!conf.id,
                readOnly: !!conf.id,
                componentCls: 'slims-wizard-position-cb',
                name: conf.id || 'empty',
                style: {
                    'background-color': conf.color || 'white',
                },
                fieldStyle: {
                    'margin-top': '6px',
                    'margin-left': '0px',
                    'background-color': conf.color || 'white'
                },
                sample_data: conf.sample_data || {}
            });
            items.push({
                xtype: 'container',
                border: true,
                items: cb,
                layout: {
                    type: 'vbox',
                    align: 'center',
                    pack: 'middle'
                },
                height: 30,
                width: 30,
                margin: 3,
                style: {
                    'background-color': conf.color || 'white',
                    'border': conf.id ? '1px solid darkred;' : '1px solid lightgray;'
                }
            });
        }
        var rowPanel = Ext.create('Ext.panel.Panel', {
            layout: 'hbox',
            items: items
        });
        return rowPanel;
    }
});