Ext.define('YMPI.store.s_lembur', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.lemburStore',
	model	: 'YMPI.model.m_lembur',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'lembur',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_lembur/getAll',
			create	: 'c_lembur/save',
			update	: 'c_lembur/save',
			destroy	: 'c_lembur/delete'
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