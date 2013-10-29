Ext.define('YMPI.store.s_jenispotongan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.jenispotonganStore',
	model	: 'YMPI.model.m_jenispotongan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'jenispotongan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_jenispotongan/getAll',
			create	: 'c_jenispotongan/save',
			update	: 'c_jenispotongan/save',
			destroy	: 'c_jenispotongan/delete'
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