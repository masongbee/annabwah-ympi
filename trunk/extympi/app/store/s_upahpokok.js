Ext.define('YMPI.store.s_upahpokok', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.upahpokokStore',
	model	: 'YMPI.model.m_upahpokok',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'upahpokok',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_upahpokok/getAll',
			create	: 'c_upahpokok/save',
			update	: 'c_upahpokok/save',
			destroy	: 'c_upahpokok/delete'
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