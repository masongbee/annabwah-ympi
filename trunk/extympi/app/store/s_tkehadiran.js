Ext.define('YMPI.store.s_tkehadiran', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.tkehadiranStore',
	model	: 'YMPI.model.m_tkehadiran',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'tkehadiran',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tkehadiran/getAll',
			create	: 'c_tkehadiran/save',
			update	: 'c_tkehadiran/save',
			destroy	: 'c_tkehadiran/delete'
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