Ext.define('Slims.reader.HasOneReader', {
    extend: 'Ext.data.reader.Json',
    alias: 'reader.hasone',
    extractData: function(root) {
        var recordName = this.record,
            data = [],
            length, i;

        if (recordName) {
            length = root.length;

            if (!length && Ext.isObject(root)) {
                length = 1;
                root = [root];
            }

            for (i = 0; i < length; i++) {
                data[i] = root[i][recordName];
            }
        } else {
            data = root;
        }
        return this.callSuper([data]);
    }
});
