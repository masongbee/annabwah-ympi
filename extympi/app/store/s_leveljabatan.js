Ext.define('YMPI.store.s_leveljabatan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.leveljabatanStore',
	model	: 'YMPI.model.m_leveljabatan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'leveljabatan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_leveljabatan/getAll',
			create	: 'c_leveljabatan/save',
			update	: 'c_leveljabatan/save',
			destroy	: 'c_leveljabatan/delete'
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