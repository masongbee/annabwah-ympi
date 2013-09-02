Ext.define('YMPI.store.s_jemputankar', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.jemputankarStore',
	model	: 'YMPI.model.m_jemputankar',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'jemputankar',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_jemputankar/getAll',
			create	: 'c_jemputankar/save',
			update	: 'c_jemputankar/save',
			destroy	: 'c_jemputankar/delete'
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