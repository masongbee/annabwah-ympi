Ext.define('YMPI.store.PermissionGroup', {
    extend	: 'Ext.data.Store',
    alias	: 'widget.PermissionGroupStore',
    model	: 'YMPI.model.PermissionGroup',
    
    autoLoad	: false,
    autoSync	: false,
    
    storeId		: 'PermissionGroup',
    
    //pageSize	: 5, // number display per Grid
    
    proxy: {
        type: 'ajax',
        api: {
		    read    : 'c_permissiongroup/getAll',
		    create	: 'c_permissiongroup/save',
		    update	: 'c_permissiongroup/save',
		    destroy	: 'c_permissiongroup/delete'
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
