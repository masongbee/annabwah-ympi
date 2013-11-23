Ext.define('YMPI.store.s_rinciancuti', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.rinciancutiStore',
	model	: 'YMPI.model.m_rinciancuti',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'rinciancuti',
	
	pageSize	: 100, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_rinciancuti/getAll',
			create	: 'c_rinciancuti/save',
			update	: 'c_rinciancuti/save',
			destroy	: 'c_rinciancuti/delete'
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