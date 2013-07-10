Ext.define('YMPI.store.s_uangsimpati', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.uangsimpatiStore',
	model	: 'YMPI.model.m_uangsimpati',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'uangsimpati',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_uangsimpati/getAll',
			create	: 'c_uangsimpati/save',
			update	: 'c_uangsimpati/save',
			destroy	: 'c_uangsimpati/delete'
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