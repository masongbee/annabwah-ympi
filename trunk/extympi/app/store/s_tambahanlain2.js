Ext.define('YMPI.store.s_tambahanlain2', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.tambahanlain2Store',
	model	: 'YMPI.model.m_tambahanlain2',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'tambahanlain2',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tambahanlain2/getAll',
			create	: 'c_tambahanlain2/save',
			update	: 'c_tambahanlain2/save',
			destroy	: 'c_tambahanlain2/delete'
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