Ext.define('App.view.groups.Window', {
    extend: 'Ext.window.Window',
    xtype: 'researchgroups-window',

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
            maxLength: 255,
            fieldLabel: 'Group name',
            value: this.record == null ? '' : this.record.get('name'),
            labelAlign: 'top',
            labelStyle: 'margin-bottom: 3px;',
            width: '100%',
            margin: 10
        }];

        this.bbar = [
            '->', {
            text: 'Save',
            icon: '/images/save.png',
            name: 'save',
            scope: this,
            handler: function() {
                var group = this.record;
                if (!group) {
                    group = Ext.create('App.model.ResearchGroup');
                }

                group.set('name', this.down('textfield').getValue());

                this.fireEvent('save', group, this);
            }
        }, '-', {
            text: 'Cancel',
            icon: '/images/delete.gif',
            scope: this,
            handler: this.close
        }];

        this.callParent();
    }
});