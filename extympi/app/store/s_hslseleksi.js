Ext.define('YMPI.store.s_hslseleksi', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.hslseleksiStore',
	model	: 'YMPI.model.m_hslseleksi',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'hslseleksi',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_hslseleksi/getAll',
			create	: 'c_hslseleksi/save',
			update	: 'c_hslseleksi/save',
			destroy	: 'c_hslseleksi/delete'
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