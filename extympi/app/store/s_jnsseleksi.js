Ext.define('YMPI.store.s_jnsseleksi', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.jnsseleksiStore',
	model	: 'YMPI.model.m_jnsseleksi',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'jnsseleksi',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_jnsseleksi/getAll',
			create	: 'c_jnsseleksi/save',
			update	: 'c_jnsseleksi/save',
			destroy	: 'c_jnsseleksi/delete'
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