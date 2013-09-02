Ext.define('YMPI.store.s_karyawan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.karyawanStore',
	model	: 'YMPI.model.m_karyawan',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'karyawan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_karyawan/getAll',
			create	: 'c_karyawan/save',
			update	: 'c_karyawan/save',
			destroy	: 'c_karyawan/delete'
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