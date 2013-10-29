Ext.define('YMPI.store.s_pcicilan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.pcicilanStore',
	model	: 'YMPI.model.m_pcicilan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'pcicilan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_pcicilan/getAll',
			create	: 'c_pcicilan/save',
			update	: 'c_pcicilan/save',
			destroy	: 'c_pcicilan/delete'
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