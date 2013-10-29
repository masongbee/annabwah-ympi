Ext.define('YMPI.store.s_jenistambahan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.jenistambahanStore',
	model	: 'YMPI.model.m_jenistambahan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'jenistambahan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_jenistambahan/getAll',
			create	: 'c_jenistambahan/save',
			update	: 'c_jenistambahan/save',
			destroy	: 'c_jenistambahan/delete'
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