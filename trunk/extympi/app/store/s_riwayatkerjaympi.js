Ext.define('YMPI.store.s_riwayatkerjaympi', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.riwayatkerjaympiStore',
	model	: 'YMPI.model.m_riwayatkerjaympi',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'riwayatkerjaympi',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_riwayatkerjaympi/getAll',
			create	: 'c_riwayatkerjaympi/save',
			update	: 'c_riwayatkerjaympi/save',
			destroy	: 'c_riwayatkerjaympi/delete'
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