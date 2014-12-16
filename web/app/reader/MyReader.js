Ext.define('Slims.reader.MyReader', {
    extend: 'Ext.data.reader.Json',
    alias: 'reader.myreader',
    readAssociated: function(record, data) {
        var associations = record.associations.items,
            i            = 0,
            length       = associations.length,
            association, associationData, proxy, reader;

        for (; i < length; i++) {
            association     = associations[i];
            associationData = this.getAssociatedDataRoot(data, association.associationKeyFunction || association.associationKey || association.name);

            if (associationData) {
                reader = association.getReader();
                if (!reader) {
                    proxy = association.associatedModel.getProxy();
                    // if the associated model has a Reader already, use that, otherwise attempt to create a sensible one
                    if (proxy) {
                        reader = proxy.getReader();
                    } else {
                        reader = new this.constructor({
                            model: association.associatedName
                        });
                    }
                }
                association.read(record, reader, associationData);
            }
        }
    }
});
