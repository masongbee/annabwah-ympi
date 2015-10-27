Ext.define('YMPI.store.s_karyawan_byunitkerja', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.karyawan_byunitkerjaStore',
	model	: 'YMPI.model.m_karyawan_byunitkerja',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'karyawan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_public_function/getKaryawanByUnitKerja'
		},
		actionMethods: {
			read    : 'POST'
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