Ext.define('YMPI.store.s_jenisabsen', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.jenisabsenStore',
	model	: 'YMPI.model.m_jenisabsen',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'jenisabsen',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_jenisabsen/getAll',
			create	: 'c_jenisabsen/save',
			update	: 'c_jenisabsen/update',
			destroy	: 'c_jenisabsen/delete'
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