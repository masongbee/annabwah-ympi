Ext.define('YMPI.store.s_potongan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.potonganStore',
	model	: 'YMPI.model.m_potongan',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'potongan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_potongan/getAll',
			create	: 'c_potongan/save',
			update	: 'c_potongan/save',
			destroy	: 'c_potongan/delete'
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