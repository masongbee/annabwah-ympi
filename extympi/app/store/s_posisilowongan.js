Ext.define('YMPI.store.s_posisilowongan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.posisilowonganStore',
	model	: 'YMPI.model.m_posisilowongan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'posisilowongan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_posisilowongan/getAll',
			create	: 'c_posisilowongan/save',
			update	: 'c_posisilowongan/save',
			destroy	: 'c_posisilowongan/delete'
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