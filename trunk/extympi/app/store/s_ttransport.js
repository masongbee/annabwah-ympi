Ext.define('YMPI.store.s_ttransport', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.ttransportStore',
	model	: 'YMPI.model.m_ttransport',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'ttransport',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_ttransport/getAll',
			create	: 'c_ttransport/save',
			update	: 'c_ttransport/save',
			destroy	: 'c_ttransport/delete'
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