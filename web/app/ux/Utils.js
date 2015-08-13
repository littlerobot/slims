Ext.define('Slims.ux.Utils', {
    extend: 'Ext.util.Observable',
    alternateClassName: 'Utils',
    singleton: true,

    rendererColorColumn: function(value) {
        return Ext.String.format('<div style="width: 15px; height: 15px; background-color: {0}; border: 1px solid black;">&nbsp;</div>', value);
    }

});