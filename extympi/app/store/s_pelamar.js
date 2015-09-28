Ext.define('YMPI.store.s_pelamar', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.pelamarStore',
	model	: 'YMPI.model.m_pelamar',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'pelamar',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_pelamar/getAll',
			create	: 'c_pelamar/save',
			update	: 'c_pelamar/save',
			destroy	: 'c_pelamar/delete'
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