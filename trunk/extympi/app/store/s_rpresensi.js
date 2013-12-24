Ext.define('YMPI.store.s_rpresensi', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.rpresensiStore',
	model	: 'YMPI.model.m_rpresensi',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'rpresensi',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_rpresensi/getAll',
			create	: 'c_rpresensi/save',
			update	: 'c_rpresensi/save',
			destroy	: 'c_rpresensi/delete'
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