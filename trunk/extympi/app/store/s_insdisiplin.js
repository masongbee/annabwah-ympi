Ext.define('YMPI.store.s_insdisiplin', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.insdisiplinStore',
	model	: 'YMPI.model.m_insdisiplin',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'insdisiplin',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_insdisiplin/getAll',
			create	: 'c_insdisiplin/save',
			update	: 'c_insdisiplin/save',
			destroy	: 'c_insdisiplin/delete'
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