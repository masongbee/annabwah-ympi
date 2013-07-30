Ext.define('YMPI.store.s_tbhs', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.tbhsStore',
	model	: 'YMPI.model.m_tbhs',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'tbhs',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tbhs/getAll',
			create	: 'c_tbhs/save',
			update	: 'c_tbhs/save',
			destroy	: 'c_tbhs/delete'
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