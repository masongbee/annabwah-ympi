Ext.define('YMPI.store.s_jenistraining', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.jenistrainingStore',
	model	: 'YMPI.model.m_jenistraining',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'jenistraining',
	
	pageSize	: 100, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_jenistraining/getAll',
			create	: 'c_jenistraining/save',
			update	: 'c_jenistraining/save',
			destroy	: 'c_jenistraining/delete'
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