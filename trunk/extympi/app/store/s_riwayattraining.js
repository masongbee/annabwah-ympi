Ext.define('YMPI.store.s_riwayattraining', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.riwayattrainingStore',
	model	: 'YMPI.model.m_riwayattraining',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'riwayattraining',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_riwayattraining/getAll',
			create	: 'c_riwayattraining/save',
			update	: 'c_riwayattraining/save',
			destroy	: 'c_riwayattraining/delete'
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