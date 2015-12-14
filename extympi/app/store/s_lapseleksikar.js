Ext.define('YMPI.store.s_lapseleksikar', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.lapseleksikarStore',
	model	: 'YMPI.model.m_lapseleksikar',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'lapseleksikar',
	
	pageSize	: 10000, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_public_function/getLapSeleksiKar'
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