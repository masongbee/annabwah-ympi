Ext.define('YMPI.store.s_tambahan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.tambahanStore',
	model	: 'YMPI.model.m_tambahan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'tambahan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tambahan/getAll',
			create	: 'c_tambahan/save',
			update	: 'c_tambahan/save',
			destroy	: 'c_tambahan/delete'
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