Ext.define('Slims.view.users.Window', {
    extend: 'Ext.window.Window',
    xtype: 'userwindow',

    requires: [
        'Ext.form.field.Text',
        'Ext.toolbar.Toolbar',
        'Ext.form.field.Checkbox'
    ],

    width: 400,
    layout: 'fit',
    modal: true,

    // user for editing
    record: null,

    initComponent: function() {
        this.setWindowTitle();

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
                allowOnlyWhitespace: false
            }, {
                xtype: 'textfield',
                name: 'name',
                fieldLabel: 'Name',
                maxLength: 255,
                allowOnlyWhitespace: false
            }, {
                xtype: 'combobox',
                name: 'research_group',
                fieldLabel: 'Research group',
                editable: false,
                store: Ext.StoreMgr.get('researchGroups'),
                queryMode: 'local',
                displayField: 'name',
                valueField: 'id'
            }, {
                xtype: 'checkbox',
                name: 'is_active',
                fieldLabel: 'Is active',
                inputValue: true
            }]
        }];

        this.bbar = [
            '->', {
            text: 'Save',
            icon: '/resources/images/save.png',
            width: 80,
            name: 'save',
            scope: this,
            handler: function() {
                var user = this.record;

                if (!this.down('form').getForm().isValid())
                    return;

                var formValues = this.down('form').getForm().getValues();

                if (!formValues.research_group) {
                    formValues.research_group = null;
                }

                if (!user) {
                    user = Ext.create('Slims.model.User', formValues);
                } else {
                    user.set(formValues);
                }

                this.fireEvent('save', user, this);
            }
        }, {
            text: 'Cancel',
            icon: '/resources/images/cancel.png',
            width: 80,
            scope: this,
            handler: this.close
        }];

        this.on('afterrender', this.setupData, this);

        this.callParent();
    },

    setWindowTitle: function() {
        if (this.record == null) {
            this.title = 'Add new user';
        } else {
            var username = this.record.get('username');
            this.title = username ? Ext.String.format('Edit user "{0}"', username) : 'Edit user';
        }
    },

    setupData: function() {
        if (!this.record)
            return;

        var user = this.record.getData();

        // prepare data for combobox
        user.research_group = user.research_group ? user.research_group.id : '';

        this.down('form').getForm().setValues(user);
    }
});
