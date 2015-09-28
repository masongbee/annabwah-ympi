Ext.define('YMPI.store.s_lowongan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.lowonganStore',
	model	: 'YMPI.model.m_lowongan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'lowongan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_lowongan/getAll',
			create	: 'c_lowongan/save',
			update	: 'c_lowongan/save',
			destroy	: 'c_lowongan/delete'
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