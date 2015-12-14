Ext.define('YMPI.store.s_lapkarlembur', {
	extend	: 'Ext.data.Store',
	model	: 'YMPI.model.m_lapkarlembur',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'lapkarlembur',
	
	pageSize	: 10000, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_lapkarlembur/lapkarlembur'
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