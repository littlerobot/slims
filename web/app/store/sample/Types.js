Ext.define('Slims.store.sample.Types', {
    extend: 'Ext.data.Store',

    model: 'Slims.model.sample.Type',

    proxy: {
        type: 'memory',
        data: [{
            id: 2, name: 'Hello', attributes: [{name: 'atrti', value: 'value'}, {name: 'atrti', value: 'value'}, {name: 'atrti', value: 'value'}, {name: 'atrti', value: 'value'}]
        },{
            id: 22, name: 'Hello dertre', attributes: [{name: 'atrti', value: 'value'}, {name: 'atrti', value: 'value'}, {name: 'atrti', value: 'value'}, {name: 'atrti', value: 'value'}]
        },{
            id: 221, name: 'Hello et4r3444', attributes: [{name: 'atrti', value: 'value'}, {name: 'atrti', value: 'value'}, {name: 'atrti', value: 'value'}, {name: 'atrti', value: 'value'}]
        }],
        // url: Slims.Url.getRoute('getsampletypes'),
        reader: {
            type: 'json',
            root: 'sample_types'
        }
    }
});