Ext.define('Slims.view.sample.search.FiltersForm', {
    extend: 'Ext.form.Panel',
    xtype: 'samplessearchfilter',

    width: '100%',
    layout: 'hbox',
    bodyPadding: 5,
    MIN_TEXT_LENGTH: 1,

    initComponent: function() {
        this.items = [{
            xtype: 'container',
            defaults: {
                padding: 5,
                labelWidth: 200,
                labelAlign: 'right',
                width: '100%'
            },
            flex: 1,
            layout: 'vbox',
            items: [{
                xtype: 'textfield',
                name: 'name',
                fieldLabel: 'Sample name',
                minLength: this.MIN_TEXT_LENGTH,
                plugins: ['clearbutton']
            }, {
                xtype: 'textfield',
                name: 'user',
                fieldLabel: 'User name',
                minLength: this.MIN_TEXT_LENGTH,
                plugins: ['clearbutton']
            }, {
                xtype: 'datefield',
                name: 'stored_start',
                fieldLabel: 'Date stored from',
                plugins: ['clearbutton']
            }]
        }, {
            xtype: 'container',
            defaults: {
                padding: 5,
                labelWidth: 200,
                labelAlign: 'right',
                width: '100%'
            },
            flex: 1,
            layout: 'vbox',
            items: [{
                xtype: 'textfield',
                name: 'passage_number',
                fieldLabel: 'Passage number',
                minLength: this.MIN_TEXT_LENGTH,
                plugins: ['clearbutton']
            }, {
                xtype: 'textfield',
                name: 'container',
                fieldLabel: 'Container mame',
                emptyText: 'e.g. Dewey: Stack 01: Box 09',
                submitEmptyText: false,
                minLength: this.MIN_TEXT_LENGTH,
                plugins: ['clearbutton']
            }, {
                xtype: 'datefield',
                name: 'stored_end',
                fieldLabel: 'Date stored to',
                plugins: ['clearbutton']
            }, {
                align: 'left',
                xtype: 'label',
                name: 'errorLabel',
                style: 'color: red; padding-right: 10px;'
            }]
        }];

        this.buttons = [{
            text: 'Search',
            name: 'search',
            handler: this.validateAndSearch,
            scope: this
        }];

        this.callParent();
    },

    showError: function(text) {
        var errorLabel = this.down('[name=errorLabel]');
        errorLabel.setText(text);
        errorLabel.el.show();
        errorLabel.el.setStyle('opacity', '1');
        setTimeout(function() {
            errorLabel.el.fadeOut({opacity: 0, duration: 1000});
        }, 3000);
    },

    validateAndSearch: function() {
        if (!this.getForm().isValid())
            return;

        var fValues = this.getForm().getValues(),
            hasValue = false;

        for (var opt in fValues) {
            if (fValues[opt]) {
                hasValue = true;
                break;
            }
        }
        if (!hasValue) {
            Ext.Msg.alert('Filter is empty', 'Please enter at least one search parameter before searching.');
            return;
        }

        if (fValues.stored_end && !fValues.stored_start || fValues.stored_start && !fValues.stored_end) {
            Ext.Msg.alert('Both dates must be entered', 'Both date fields must be populated to search for matching samples.');
            return;
        }

        this.fireEvent('search', fValues);
    }
});
