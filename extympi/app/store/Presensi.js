Ext.define('YMPI.store.Presensi', {
    extend	: 'Ext.data.Store',
    alias	: 'widget.PresensiStore',
    model	: 'YMPI.model.Presensi',
    
    autoLoad	: false,
    autoSync	: false,
    
    storeId		: 'PresensiStore',
    
    pageSize	: 10, // number display per Grid
    
    proxy: {
        type: 'ajax',
        api: {
		    read    : 'c_presensi/getAll',
		    create	: 'c_presensi/save',
		    update	: 'c_presensi/save',
		    destroy	: 'c_presensi/delete'
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
