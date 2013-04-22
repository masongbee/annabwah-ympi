Ext.define('YMPI.store.kompensasicuti', {
    extend	: 'Ext.data.Store',
    alias	: 'widget.kompensasicutiStore',
    model	: 'YMPI.model.kompensasicuti',
    
    autoLoad	: false,
    autoSync	: false,
    
    storeId		: 'kompensasicutiStore',
    
    pageSize	: 10, // number display per Grid
    
    proxy: {
        type: 'ajax',
        api: {
		    read    : 'c_kompensasicuti/getAll',
		    create	: 'c_kompensasicuti/save',
		    update	: 'c_kompensasicuti/save',
		    destroy	: 'c_kompensasicuti/delete'
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
