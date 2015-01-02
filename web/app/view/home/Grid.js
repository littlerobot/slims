Ext.define('Slims.view.home.Grid', {
    extend: 'Ext.tree.Panel',
    xtype: 'containersgrid',

    requires: [
        'Ext.Msg',
        'Ext.ux.CheckColumn',
        'Slims.model.Container'
    ],

    useArrows: true,
    rootVisible: false,
    multiSelect: true,

    initComponent: function() {
        this.tbar = [{
            xtype: 'button',
            text: 'Add Container',
            icon: '/resources/images/add.png',
            name: 'addContainer'
        }, '->', {
            width: 25,
            icon: '/resources/images/reload.png',
            name: 'reloadGrid'
        }];

        this.store = Ext.create('Slims.store.Containers');

        this.columns = [{
            xtype: 'treecolumn',
            text: 'Container',
            flex: 3,
            sortable: true,
            dataIndex: 'name'
        }, {
            text: 'Research group',
            flex: 2,
            dataIndex: 'research_group',
            renderer: function(value) {
                return value ? value.name : '';
            }
        }, {
            text: 'Capacity',
            style: 'text-align: middle;',
            columns: [{
                text: 'Remaining',
                width: 85,
                dataIndex: 'sample_remaining_capacity'
            }, {
                text: 'Total',
                width: 55,
                dataIndex: 'sample_total_capacity'
            }]
        }, {
            width: 30,
            dataIndex: 'colour',
            renderer: function(value) {
                return '<div style="width: 15px; height: 15px; background-color: '+value+'; border: 1px solid black;">&nbsp;</div>';
            }
        }, {
            xtype: 'actioncolumn',
            icon: '/resources/images/edit.png',
            width: 30,
            menuDisabled: true,
            tooltip: 'Edit container',
            scope: this,
            handler: function(grid, rowIndex, colIndex) {
                var rec = grid.getStore().getAt(rowIndex);
                this.fireEvent('editrecord', rec);
            }
        }];

        this.callParent();
    }
});
