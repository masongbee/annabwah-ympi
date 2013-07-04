Ext.define('YMPI.store.s_mohoncuti', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.mohoncutiStore',
	model	: 'YMPI.model.m_mohoncuti',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'mohoncuti',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_mohoncuti/getAll',
			create	: 'c_mohoncuti/save',
			update	: 'c_mohoncuti/save',
			destroy	: 'c_mohoncuti/delete'
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