Ext.define('YMPI.store.Jabatan', {
    extend	: 'Ext.data.Store',
    alias	: 'widget.jabatanStore',
    model	: 'YMPI.model.Jabatan',
    
    autoLoad	: true,
    autoSync	: false,
    
    storeId		: 'jabatan',
    
    pageSize	: 10, // number display per Grid
    
    proxy: {
        type: 'ajax',
        api: {
		    read    : base_url + 'welcome/jabatan/getAll',
		    create	: base_url + 'welcome/jabatan/save',
		    update	: base_url + 'welcome/jabatan/save',
		    destroy	: base_url + 'welcome/jabatan/delete'
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
