Ext.define('YMPI.store.s_lapgaji', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.lapgajiStore',
	model	: 'YMPI.model.m_lapgaji',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'lapgaji',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_lapgaji/getAll'
		},
		actionMethods: {
			read    : 'POST'
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