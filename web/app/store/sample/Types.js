Ext.define('Slims.store.sample.Types', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.sample.Type',

    proxy: {
        type: 'memory',
        data: [{
            id: 2, name: 'Hello', attributes: '1, 23,,4,4,34'
        },{
            id: 22, name: 'Hello dertre', attributes: '1, 23,,4,4,34'
        },{
            id: 221, name: 'Hello et4r3444', attributes: '1, 23,,4,4,34'
        }],
        // url: Slims.Url.getRoute('getsampletypes'),
        reader: {
            type: 'json',
            root: 'sample_types'
        }
    }
});