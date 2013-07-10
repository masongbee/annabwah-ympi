Ext.define('YMPI.store.s_tshift', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.tshiftStore',
	model	: 'YMPI.model.m_tshift',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'tshift',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tshift/getAll',
			create	: 'c_tshift/save',
			update	: 'c_tshift/save',
			destroy	: 'c_tshift/delete'
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