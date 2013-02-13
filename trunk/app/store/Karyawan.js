Ext.define('YMPI.store.Karyawan', {
    extend	: 'Ext.data.Store',
    alias	: 'widget.KaryawanStore',
    model	: 'YMPI.model.Karyawan',
    
    autoLoad	: true,
    autoSync	: false,
    
    storeId		: 'karyawan',
    
    pageSize	: 10, // number display per Grid
    
    proxy: {
        type: 'ajax',
        api: {
		    read    : base_url + 'welcome/karyawan/getAll',
		    create	: base_url + 'welcome/karyawan/save',
		    update	: base_url + 'welcome/karyawan/save',
		    destroy	: base_url + 'welcome/karyawan/delete'
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
