Ext.define('YMPI.store.UserGroup', {
    extend	: 'Ext.data.Store',
    alias	: 'widget.UserGroupStore',
    model	: 'YMPI.model.UserGroup',
    
    autoLoad	: true,
    autoSync	: false,
    
    storeId		: 'UserGroup',
    
    pageSize	: 10, // number display per Grid
    
    proxy: {
        type: 'ajax',
        api: {
		    read    : 'c_usergroup/getAll',
		    create	: 'c_usergroup/save',
		    update	: 'c_usergroup/save',
		    destroy	: 'c_usergroup/delete'
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
