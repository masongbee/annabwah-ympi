Ext.define('YMPI.store.s_hitungpresensi', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.hitungpresensiStore',
	model	: 'YMPI.model.m_hitungpresensi',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'hitungpresensi',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_hitungpresensi/getAll',
			create	: 'c_hitungpresensi/save',
			update	: 'c_hitungpresensi/save',
			destroy	: 'c_hitungpresensi/delete'
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
    //remoteSort: false,
	
	constructor: function(){
		this.callParent(arguments);
	}
	
});