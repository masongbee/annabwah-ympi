Ext.define('YMPI.store.s_penugasankar', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.penugasankarStore',
	model	: 'YMPI.model.m_penugasankar',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'penugasankar',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_penugasankar/getAll',
			create	: 'c_penugasankar/save',
			update	: 'c_penugasankar/save',
			destroy	: 'c_penugasankar/delete'
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