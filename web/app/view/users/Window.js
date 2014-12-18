Ext.define('Slims.view.users.Window', {
    extend: 'Ext.window.Window',
    xtype: 'userwindow',

    requires: [
        'Ext.form.field.Text',
        'Ext.toolbar.Toolbar'
    ],

    width: 400,
    layout: 'fit',
    modal: true,

    // user for editing
    record: null,

    initComponent: function() {
        this.title = this.record == null ? 'Add new user' : 'Edit user';

        this.items = [{
            xtype: 'form',

            defaults: {
                labelStyle: 'margin-bottom: 3px;',
                anchor: '100%',
                labelAlign: 'top',
                margin: 10
            },
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Username',
                maxLength: 10,
                allowBlank: false
            }, {
                xtype: 'textfield',
                fieldLabel: 'Name',
                maxLength: 255,
                allowBlank: false
            }, {
                xtype: 'combobox',
                fieldLabel: 'Research Group',
                store: Ext.StoreMgr.get('researchGroups'),
                queryMode: 'local',
                displayField: 'name',
                valueField: 'id'
            }]
        }];

        this.bbar = [
            '->', {
            text: 'Save',
            icon: '/resources/images/save.png',
            name: 'save',
            scope: this,
            handler: function() {
                var group = this.record;

                if (!this.down('textfield').isValid())
                    return;

                if (!group) {
                    group = Ext.create('Slims.model.ResearchGroup');
                }

                group.set('name', this.down('textfield').getValue());

                this.fireEvent('save', group, this);
            }
        }, '-', {
            text: 'Cancel',
            icon: '/resources/images/cancel.png',
            scope: this,
            handler: this.close
        }];

        this.callParent();
    }
});