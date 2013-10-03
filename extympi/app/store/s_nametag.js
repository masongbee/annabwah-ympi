Ext.define('YMPI.store.s_nametag', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.nametagStore',
	model	: 'YMPI.model.m_nametag',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'nametag',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_nametag/getAll',
			create	: 'c_nametag/save',
			update	: 'c_nametag/save',
			destroy	: 'c_nametag/delete'
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