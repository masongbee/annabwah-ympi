Ext.define('YMPI.store.s_tahapseleksi', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.tahapseleksiStore',
	model	: 'YMPI.model.m_tahapseleksi',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'tahapseleksi',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tahapseleksi/getAll',
			create	: 'c_tahapseleksi/save',
			update	: 'c_tahapseleksi/save',
			destroy	: 'c_tahapseleksi/delete'
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