Ext.define('YMPI.store.s_penghargaan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.penghargaanStore',
	model	: 'YMPI.model.m_penghargaan',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'penghargaan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_penghargaan/getAll',
			create	: 'c_penghargaan/save',
			update	: 'c_penghargaan/save',
			destroy	: 'c_penghargaan/delete'
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