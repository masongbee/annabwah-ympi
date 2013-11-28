Ext.define('YMPI.store.s_detilshift', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.detilshiftStore',
	model	: 'YMPI.model.m_detilshift',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'detilshift',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_detilshift/getAll',
			create	: 'c_detilshift/save',
			update	: 'c_detilshift/save',
			destroy	: 'c_detilshift/delete'
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