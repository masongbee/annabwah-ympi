Ext.define('YMPI.store.s_tkeluarga', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.tkeluargaStore',
	model	: 'YMPI.model.m_tkeluarga',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'tkeluarga',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tkeluarga/getAll',
			create	: 'c_tkeluarga/save',
			update	: 'c_tkeluarga/save',
			destroy	: 'c_tkeluarga/delete'
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