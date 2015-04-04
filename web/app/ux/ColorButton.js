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
            text: 'Select color',
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

    getValue: function() {
    	return '#' + this.down('colorpicker').getValue();
    },

    setValue: function(color) {
        if (!color)
            return;

        this.down('container[name=colorPalette]').el.setStyle('background', color);
        color = color.replace('#', '');
        var picker = this.down('colorpicker');
        // add color if it isn't in palette yet for resolving exception
        if (picker.colors.indexOf(color) == -1) {
            picker.colors.push(color);
        }

        picker.select(color);
    }
});