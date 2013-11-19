Ext.define('YMPI.store.s_permohonancuti', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.permohonancutiStore',
	model	: 'YMPI.model.m_permohonancuti',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'permohonancuti',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_permohonancuti/getAll',
			create	: 'c_permohonancuti/save',
			update	: 'c_permohonancuti/save',
			destroy	: 'c_permohonancuti/delete'
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