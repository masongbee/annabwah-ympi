Ext.define('YMPI.store.s_riwayatsehat', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.riwayatsehatStore',
	model	: 'YMPI.model.m_riwayatsehat',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'riwayatsehat',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_riwayatsehat/getAll',
			create	: 'c_riwayatsehat/save',
			update	: 'c_riwayatsehat/save',
			destroy	: 'c_riwayatsehat/delete'
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