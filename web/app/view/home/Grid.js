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
    readOnly: false,

    initComponent: function() {
        if (!this.readOnly) {
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
        }

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
                width: 100,
                sortable: true,
                dataIndex: 'sample_remaining_capacity'
            }, {
                text: 'Total',
                width: 65,
                sortable: true,
                dataIndex: 'sample_total_capacity'
            }]
        }, {
            xtype: 'typecolumn',
            type: 'colour',
            dataIndex: 'colour',
            sortable: false,
            menuDisabled: true
        }];

        if (!this.readOnly) {
            this.columns.push({
                xtype: 'actioncolumn',
                hidden: this.readOnly,
                icon: '/resources/images/edit.png',
                width: 35,
                menuDisabled: true,
                tooltip: 'Edit container',
                scope: this,
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    this.fireEvent('editrecord', rec);
                }
            });
        }

        this.viewConfig = {
            loadMask: true
        };

        this.callParent();
    },

    reload: function(path) {
        if (path) {
            this.getStore().on('load', function() {
                this.expandPath(path);
            }, this, {single: true})
        }
        this.getStore().reload();
    }
});
