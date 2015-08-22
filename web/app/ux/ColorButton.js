Ext.define('Slims.ux.ColorButton', {
    extend: 'Ext.container.Container',
    xtype: 'colorbutton',

    requires: ['Ext.picker.Color'],

    layout: {
        type: 'hbox',
        align: 'middle'
    },
    labelWidth: 0,
    fieldLabel: '',
    buttonText: 'Select color',

    initComponent: function() {
        this.items = [{
            xtype: 'form',
            width: this.labelWidth ? this.labelWidth + 5 : 0,
            items: [{
                xtype: 'label',
                width: this.labelWidth,
                text: this.fieldLabel ? this.fieldLabel + ":" : ''
            }]
        }, {
            xtype: 'panel',
            border: true,
            layout: 'fit',
            height: 24,
            width: 24,
            items: [{
                xtype: 'container',
                name: 'colorPalette',
                listeners: {
                    afterrender: function(container) {
                        container.el.dom.removeChild(container.el.dom.childNodes[0]);
                    }
                }
            }]
        }, {
            xtype: 'button',
            style: 'margin-left: 8px;',
            text: this.buttonText,
            width: 110,
            name: 'colorButton',
            menu: [{
                xtype: 'colorpicker',
                name: 'colour',
                value: 'FFFFFF',
                listeners: {
                    scope: this,
                    select: function(picker, selColor) {
                        picker.up('button[name=colorButton]').menu.hide();
                        this.down('container[name=colorPalette]').el.setStyle('background', '#'+selColor);
                    }
                }
            }]
        }];

        this.callParent();
    },

    setValue: function(color) {
        if (!color)
            return;

        var palette = this.down('container[name=colorPalette]'),
            paletteEl = palette.el;

        if (paletteEl) {
            paletteEl.setStyle('background', color);
        } else {
            palette.style = 'background: ' + color;
        }

        color = color.replace('#', '');
        var picker = this.down('colorpicker');
        // add color if it isn't in palette yet for resolving exception
        if (picker.colors.indexOf(color) == -1) {
            picker.colors.push(color);
        }

        picker.select(color);
    },

    getValue: function() {
        return '#' + this.down('colorpicker').getValue();
    },

    getSubmitData: function() {
        var data = {};
        data[this.name] = this.getValue();

        return data;
    },

    isFormField: function() {
        return true;
    },

    getName: function() {
        return this.name;
    },

    isValid: function() {
        return true;
    },

    validate: function() {
        return true;
    }
});