Ext.define('YMPI.store.s_presensilembur', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.presensilemburStore',
	model	: 'YMPI.model.m_presensilembur',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'presensilembur',
	
	pageSize	: 18, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_presensilembur/getAll',
			create	: 'c_presensilembur/save',
			update	: 'c_presensilembur/save',
			destroy	: 'c_presensilembur/delete'
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