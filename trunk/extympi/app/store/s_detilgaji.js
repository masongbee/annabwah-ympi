Ext.define('YMPI.store.s_detilgaji', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.detilgajiStore',
	model	: 'YMPI.model.m_detilgaji',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'detilgaji',
	
	//pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_detilgaji/getAll',
			create	: 'c_detilgaji/save',
			update	: 'c_detilgaji/save',
			destroy	: 'c_detilgaji/delete'
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