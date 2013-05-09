Ext.define('YMPI.store.s_s_info', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.s_infoStore',
	model	: 'YMPI.model.m_s_info',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 's_info',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_s_info/getAll',
			create	: 'c_s_info/save',
			update	: 'c_s_info/save',
			destroy	: 'c_s_info/delete'
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