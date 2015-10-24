Ext.define('YMPI.store.Permissions2', {
    extend	: 'Ext.data.Store',
    model	: 'YMPI.model.Permissions2',
    
    autoLoad	: false,
    autoSync	: false,
    
    proxy: {
        type: 'ajax',
        api: {
		    read    : 'c_permissions2/getAll'
        },
        actionMethods: {
		    read    : 'POST'
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
