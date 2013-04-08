Ext.define('YMPI.store.Absensi', {
    extend	: 'Ext.data.Store',
    alias	: 'widget.AbsensiStore',
    model	: 'YMPI.model.Absensi',
    
    autoLoad	: false,
    autoSync	: false,
    
    storeId		: 'AbsensiStore',
    
    pageSize	: 10, // number display per Grid
    
    proxy: {
        type: 'ajax',
        api: {
		    read    : 'c_absensi/getAll',
		    create	: 'c_absensi/save',
		    update	: 'c_absensi/save',
		    destroy	: 'c_absensi/delete'
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
