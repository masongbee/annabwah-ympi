Ext.define('YMPI.store.s_tpekerjaan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.tpekerjaanStore',
	model	: 'YMPI.model.m_tpekerjaan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'tpekerjaan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tpekerjaan/getAll',
			create	: 'c_tpekerjaan/save',
			update	: 'c_tpekerjaan/save',
			destroy	: 'c_tpekerjaan/delete'
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