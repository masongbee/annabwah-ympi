Ext.define('YMPI.store.s_lapkrjkar', {
	extend	: 'Ext.data.Store',
	model	: 'YMPI.model.m_lapkrjkar',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'lapkrjkar',
	
	pageSize	: 10000, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tkinerja/lapkrjkar'
		},
		actionMethods: {
			read    : 'POST'
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