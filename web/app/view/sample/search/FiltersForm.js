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
                fieldLabel: 'Sample Name',
                minLength: this.MIN_TEXT_LENGTH,
                plugins: ['clearbutton']
            }, {
                xtype: 'textfield',
                name: 'user',
                fieldLabel: 'User Name',
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
                fieldLabel: 'Container Name',
                minLength: this.MIN_TEXT_LENGTH,
                plugins: ['clearbutton']
            }, {
                xtype: 'datefield',
                name: 'stored_end',
                fieldLabel: 'Date stored till',
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
        }]

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
            // this.showError('Please, type filter parameter(s) before searching');
            Ext.Msg.alert('Filter is empty', 'Please, type filter parameter(s) before searching');
            return;
        }

        if (fValues.stored_end && !fValues.stored_start || fValues.stored_start && !fValues.stored_end) {
            // this.showError('Please, fill in or clear both "Date stored from" and "Date stored till" fields');
            Ext.Msg.alert('Use both dates', 'Please, fill in or clear both "Date stored from" and "Date stored till" fields');
            return;
        }

        this.fireEvent('search', fValues);
    }
});