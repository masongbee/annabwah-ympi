Ext.define('YMPI.store.s_tqcp', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.tqcpStore',
	model	: 'YMPI.model.m_tqcp',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'tqcp',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tqcp/getAll',
			create	: 'c_tqcp/save',
			update	: 'c_tqcp/save',
			destroy	: 'c_tqcp/delete'
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