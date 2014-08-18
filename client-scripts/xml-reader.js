
Ext.ns('Ext.slims');

Ext.slims.XmlReader = Ext.extend(Ext.data.XmlReader, {
    constructor: function(config, records) {
        config = config || {};
        records = records || ['id', 'message'];
        config = Ext.apply({
            record: 'result',
            success: '@success'
        }, config);
        Ext.slims.XmlReader.superclass.constructor.call(this, config, records);
    }
});