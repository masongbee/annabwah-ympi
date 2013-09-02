Ext.define('YMPI.store.s_importpres', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.importpresStore',
	model	: 'YMPI.model.m_importpres',
	
	autoLoad	: true,
	autoSync	: false,
    remoteSort	: false,
	
	storeId		: 'importpres',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_importpres/getAll',
			create	: 'c_importpres/save',
			update	: 'c_importpres/save',
			destroy	: 'c_importpres/delete'
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
				//var ms = Ext.JSON.decode(operation.getError());
				//console.info(operation.getError());
				Ext.MessageBox.show({
					title: 'REMOTE EXCEPTION',
					msg: operation.getError().statusText,
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