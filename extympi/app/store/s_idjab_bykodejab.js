Ext.define('YMPI.store.s_idjab_bykodejab', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.idjab_bykodejabStore',
	model	: 'YMPI.model.m_idjab_bykodejab',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'idjab_bykodejab',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_idjab_bykodejab/getAll'
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