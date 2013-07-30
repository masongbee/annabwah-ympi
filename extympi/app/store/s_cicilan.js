Ext.define('YMPI.store.s_cicilan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.cicilanStore',
	model	: 'YMPI.model.m_cicilan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'cicilan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_cicilan/getAll',
			create	: 'c_cicilan/save',
			update	: 'c_cicilan/save',
			destroy	: 'c_cicilan/delete'
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