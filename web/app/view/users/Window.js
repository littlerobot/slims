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
                name: 'username',
                maxLength: 10,
                allowBlank: false
            }, {
                xtype: 'textfield',
                name: 'name',
                fieldLabel: 'Name',
                maxLength: 255,
                allowBlank: false
            }, {
                xtype: 'combobox',
                name: 'research_group',
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
                var user = this.record;

                if (!this.down('form').getForm().isValid())
                    return;

                if (!user) {
                    user = Ext.create('Slims.model.User', this.down('form').getForm().getValues());
                }

                this.fireEvent('save', user, this);
            }
        }, '-', {
            text: 'Cancel',
            icon: '/resources/images/cancel.png',
            scope: this,
            handler: this.close
        }];

        this.on('afterrender', this.setupData, this);

        this.callParent();
    },

    setupData: function() {
        if (this.record == null)
            return;

        var user = this.record.getData();

        // prepare data for combobox
        user.research_group = user.research_group ? user.research_group.id : '';

        this.down('form').getForm().setValues(user);
    }
});