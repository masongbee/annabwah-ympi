Ext.define('YMPI.store.s_tkinerja', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.tkinerjaStore',
	model	: 'YMPI.model.m_tkinerja',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'tkinerja',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tkinerja/getAll',
			create	: 'c_tkinerja/save',
			update	: 'c_tkinerja/save',
			destroy	: 'c_tkinerja/delete'
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