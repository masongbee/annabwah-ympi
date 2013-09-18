Ext.define('YMPI.store.s_potonganlain2', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.potonganlain2Store',
	model	: 'YMPI.model.m_potonganlain2',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'potonganlain2',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_potonganlain2/getAll',
			create	: 'c_potonganlain2/save',
			update	: 'c_potonganlain2/save',
			destroy	: 'c_potonganlain2/delete'
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