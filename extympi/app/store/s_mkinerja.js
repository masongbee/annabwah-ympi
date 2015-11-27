Ext.define('YMPI.store.s_mkinerja', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.mkinerjaStore',
	model	: 'YMPI.model.m_mkinerja',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'mkinerja',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_mkinerja/getAll',
			create	: 'c_mkinerja/save',
			update	: 'c_mkinerja/save',
			destroy	: 'c_mkinerja/delete'
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