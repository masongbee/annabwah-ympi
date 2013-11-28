Ext.define('YMPI.store.s_periodegaji', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.periodegajiStore',
	model	: 'YMPI.model.m_periodegaji',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'periodegaji',
	
	pageSize	: 18, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_periodegaji/getAll',
			create	: 'c_periodegaji/save',
			update	: 'c_periodegaji/save',
			destroy	: 'c_periodegaji/delete'
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