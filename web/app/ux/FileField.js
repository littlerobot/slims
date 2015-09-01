Ext.define('Slims.ux.FileField', {
    extend: 'Ext.form.field.File',
    xtype: 'samplefile',

    buttonText: 'Select',

    initComponent: function() {

        this.on('change', this.readFile, this);
        this.callParent();
    },

    readFile: function(field, val) {
        // replace fake path
        var node = Ext.DomQuery.selectNode('input[id='+field.getInputId()+']');
        node.value = val.replace("C:\\fakepath\\","");

        var filesList = field.el.down('input[type=file]').el.dom.files,
            file = filesList[0];

        var form = this;
        form.setLoading('Read the file...');
        var reader = new FileReader();
        reader.onload = function(e) {
            var file = e.target.result;
            field.theFile = btoa(file);
            form.setLoading(false);
        };
        field.file_name = file.name;
        reader.readAsBinaryString(file);
    },

    getSubmitData: function() {
        var data = {};
        data[this.name] = {
            file: this.theFile,
            name: this.file_name
        };

        return data;
    }
});