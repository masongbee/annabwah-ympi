Ext.define('YMPI.store.s_riwayatkerja', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.riwayatkerjaStore',
	model	: 'YMPI.model.m_riwayatkerja',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'riwayatkerja',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_riwayatkerja/getAll',
			create	: 'c_riwayatkerja/save',
			update	: 'c_riwayatkerja/save',
			destroy	: 'c_riwayatkerja/delete'
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