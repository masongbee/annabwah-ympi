Ext.define('YMPI.store.permohonanijin', {
    extend	: 'Ext.data.Store',
    alias	: 'widget.permohonanijinStore',
    model	: 'YMPI.model.permohonanijin',
    
    autoLoad	: false,
    autoSync	: false,
    
    storeId		: 'permohonanijinStore',
    
    pageSize	: 10, // number display per Grid
    
    proxy: {
        type: 'ajax',
        api: {
		    read    : 'c_permohonanijin/getAll',
		    create	: 'c_permohonanijin/save',
		    update	: 'c_permohonanijin/save',
		    destroy	: 'c_permohonanijin/delete'
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
