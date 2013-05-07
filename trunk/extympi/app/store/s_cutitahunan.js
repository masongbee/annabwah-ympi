Ext.define('YMPI.store.s_cutitahunan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.cutitahunanStore',
	model	: 'YMPI.model.m_cutitahunan',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'cutitahunan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_cutitahunan/getAll',
			create	: 'c_cutitahunan/save',
			update	: 'c_cutitahunan/save',
			destroy	: 'c_cutitahunan/delete'
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