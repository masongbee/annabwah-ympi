Ext.define('YMPI.store.Karyawan', {
    extend	: 'Ext.data.Store',
    alias	: 'widget.KaryawanStore',
    model	: 'YMPI.model.Karyawan',
    
    autoLoad	: false,
    autoSync	: false,
    
    storeId		: 'karyawan',
    
    pageSize	: 10, // number display per Grid
    
    proxy: {
        type: 'ajax',
        api: {
		    read    : 'karyawan/getAll',
		    create	: 'karyawan/save',
		    update	: 'karyawan/save',
		    destroy	: 'karyawan/delete'
        },
        actionMethods: {
		    read    : 'POST',
		    create	: 'POST',
		    update	: 'POST',
		    destroy	: 'POST'
		},
        reader: {
        	type            : 'json',
            root            : 'data',
            rootProperty    : 'data',
            successProperty : 'success',
            messageProperty : 'message'
        },
        writer: {
        	type            : 'json',
            writeAllFields  : true,
            root            : 'data',
            encode          : true
        },
        listeners: {
            exception: function(proxy, response, operation){
                Ext.MessageBox.show({
                    title: 'REMOTE EXCEPTION',
                    msg: operation.getError(),
                    icon: Ext.MessageBox.ERROR,
                    buttons: Ext.Msg.OK
                });
            }
        }
    },
    
    constructor: function(){
    	this.callParent(arguments);
    }
    
});
