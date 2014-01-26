Ext.define('YMPI.store.s_presensikhusus', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.presensikhususStore',
	model	: 'YMPI.model.m_presensikhusus',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'presensikhusus',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_presensikhusus/getAll',
			create	: 'c_presensikhusus/save',
			update	: 'c_presensikhusus/save',
			destroy	: 'c_presensikhusus/delete'
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