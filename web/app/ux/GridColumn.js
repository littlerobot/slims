/**
 *
 * Slims.ux.GridColumn class
 * extend simple grid column
 * determine column renderer according to type
 *
**/
Ext.define('Slims.ux.GridColumn', {
	extend: 'Ext.grid.column.Column',
	xtype: 'typecolumn',

	// one of ['brief-text', 'long-text', colour', 'document', 'option', 'date', 'user']
	type: 'brief-text',
	initComponent: function() {
		this.selectRendererByType();

		this.callParent();
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
			case 'brief-text':
				rendererFn = this.simpleRenderer;
				break;
		}

		this.renderer = rendererFn;
	},

	colourRenderer: function(value) {
		return Ext.String.format('<div style="width: 15px; height: 15px; background-color: {0}; border: 1px solid black;">&nbsp;</div>', value);
	},

	documentRenderer: function(value) {
		return value;
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