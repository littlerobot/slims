/**
 *
 * Slims.ux.TypeColumn class
 * extend simple grid column
 * determine column renderer according to type
 *
**/
Ext.define('Slims.ux.TypeColumn', {
    extend: 'Ext.grid.column.Column',
    xtype: 'typecolumn',

    // one of ['brief-text', 'long-text', colour', 'document', 'option', 'date', 'user']
    type: 'brief-text',
    initComponent: function() {
        this.selectRendererByType();
        this.on('afterrender', this.setTypeWidth, this);

        this.callParent();
    },

    setTypeWidth: function() {
        var width = this.getWidth();

        switch (this.type) {
            case 'colour':
                width = 120;
                break;
            case 'document':
                width = 250;
                break;
            case 'option':
                width = 350;
                break;
            case 'date':
                width = 100;
                break;
            case 'user':
                width = 120;
                break;
            case 'long-text':
                width = 300;
                break;
            case 'brief-text':
                width = 180;
                break;
        }

        this.setWidth(width);
    },

    selectRendererByType: function() {
        var rendererFn = Ext.emptyFn;

        switch (this.type) {
            case 'colour':
                rendererFn = this.colourRenderer;
                break;
            case 'document':
                rendererFn = this.documentRenderer;
                break;
            case 'option':
                rendererFn = this.optionRenderer;
                break;
            case 'date':
                rendererFn = this.dateRenderer;
                break;
            case 'user':
                rendererFn = this.userRenderer;
                break;
            case 'long-text':
                rendererFn = this.longTextRenderer;
                break;
            case 'brief-text':
                rendererFn = this.simpleRenderer;
                break;
        }

        this.renderer = rendererFn;
    },

    colourRenderer: function(value) {
        if (!value) {
            value = 'ffffff';
        }
        var hex = '#'+value.replace('#', ''); // be sure in true color format;
        return Ext.String.format('<div style="width: 15px; height: 15px; background-color: {0}; border: 1px solid black;">&nbsp;</div>', hex);
    },

    longTextRenderer: function(value, metaData) {
        if (value.length > 40) {
            metaData.tdAttr = 'data-qtip="' + value + '"';
        }

        return value;
    },

    documentRenderer: function(value) {
        return value.file ? '[' + value.name + ']' : '';
    },

    optionRenderer: function(value) {
        return value;
    },

    dateRenderer: function(value) {
        return value;
    },

    userRenderer: function(value) {
        return value;
    },

    simpleRenderer: function(value) {
        return value;
    }
});