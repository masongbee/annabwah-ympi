Ext.define('YMPI.store.s_td_evefektivitas', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.td_evefektivitasStore',
	model	: 'YMPI.model.m_td_evefektivitas',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'td_evefektivitas',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_td_evefektivitas/getAll',
			create	: 'c_td_evefektivitas/save',
			update	: 'c_td_evefektivitas/save',
			destroy	: 'c_td_evefektivitas/delete'
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