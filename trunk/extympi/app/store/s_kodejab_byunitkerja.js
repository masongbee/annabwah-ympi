Ext.define('YMPI.store.s_kodejab_byunitkerja', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.kodejab_byunitkerjaStore',
	model	: 'YMPI.model.m_kodejab_byunitkerja',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'kodejab_byunitkerja',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_kodejab_byunitkerja/getAll'
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