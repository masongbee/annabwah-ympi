Ext.define('YMPI.store.s_tmakan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.tmakanStore',
	model	: 'YMPI.model.m_tmakan',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'tmakan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tmakan/getAll',
			create	: 'c_tmakan/save',
			update	: 'c_tmakan/save',
			destroy	: 'c_tmakan/delete'
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