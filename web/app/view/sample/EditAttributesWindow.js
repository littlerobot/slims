Ext.define('Slims.view.sample.EditAttributesWindow', {
    extend: 'Ext.window.Window',
    xtype: 'editattributeswindow',
    requires: [
        'Slims.view.sample.wizard.AttributesForm'
    ],

    width: 500,
    layout: 'fit',
    height: 500,
    mode: 'store', // or remove
    sample: null,
    modal: true,

    initComponent: function() {
        this.validateRequiredParams();

        this.title = Ext.String.format('{0} Sample On Position {1}', Ext.String.capitalize(this.mode), this.sample.get('positionId'));

        this.items = [{
            xtype: 'attributesform'
        }];

        this.buttons = ['->', {
            text: 'Save',
            handler: this.save,
            scope: this
        }, {
            text: 'Cancel',
            handler: this.close,
            scope: this
        }];

        this.on('afterrender', this.setupForm, this);

        this.callParent();
    },

    setupForm: function() {
        var form = this.down('attributesform'),
            sampleData = this.sample.data;

        var instanceTemplate = Ext.StoreMgr.get('instanceTemplates').findRecord('id', this.sample.get('sampleInstanceTemplate'));
        var attributes = instanceTemplate.get(this.mode);

        form.loadAttributes(attributes);

        var attributes = {};
        for (var name in sampleData) {
            if (name.indexOf('attributes.id') == 0) {
                var id = name.replace('attributes.id', '');
                attributes[id] = sampleData[name];
            }
        }
        Ext.apply(sampleData, attributes);
        form.setValues(sampleData);
    },

    save: function() {
        var attributes = this.down('attributesform').getAttributesValues();
        var color = this.down('attributesform').getColor();

        for (var name in attributes) {
            this.sample.set('attributes.id'+name, attributes[name]);
        }
        this.sample.set('samplesColor', color);
        this.fireEvent('save', this.sample, attributes, color);
        this.close();
    },

    validateRequiredParams: function() {
        if (!this.sample) {
            console.error('EditAttributesWindow: this.sample was not defined');
        }
        if (this.mode !== 'store' && this.mode !== 'remove') {
            console.error('EditAttributesWindow: this.mode must be "store" or "remove"');
        }
    }
});