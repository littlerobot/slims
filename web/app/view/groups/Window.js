Ext.define('Slims.view.groups.Window', {
    extend: 'Ext.window.Window',
    xtype: 'groupwindow',

    requires: [
        'Ext.form.field.Text',
        'Ext.toolbar.Toolbar'
    ],

    width: 400,
    layout: 'vbox',
    modal: true,

    // group for editing
    record: null,

    initComponent: function() {
        this.title = this.record == null ? 'Add new group' : 'Edit group';

        this.items = [{
            xtype: 'textfield',
            fieldLabel: 'Group name',
            labelStyle: 'margin-bottom: 3px;',
            width: '100%',
            labelAlign: 'top',
            maxLength: 255,
            margin: 10,
            allowBlank: false,
            value: this.record == null ? '' : this.record.get('name')
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