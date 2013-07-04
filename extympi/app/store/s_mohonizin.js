Ext.define('YMPI.store.s_mohonizin', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.mohonizinStore',
	model	: 'YMPI.model.m_mohonizin',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'mohonizin',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_mohonizin/getAll',
			create	: 'c_mohonizin/save',
			update	: 'c_mohonizin/save',
			destroy	: 'c_mohonizin/delete'
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