Ext.define('YMPI.store.s_rencanalembur', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.rencanalemburStore',
	model	: 'YMPI.model.m_rencanalembur',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'rencanalembur',
	
	//pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_rencanalembur/getAll',
			create	: 'c_rencanalembur/save',
			update	: 'c_rencanalembur/save',
			destroy	: 'c_rencanalembur/delete'
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