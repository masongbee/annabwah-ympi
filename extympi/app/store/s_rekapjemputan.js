Ext.define('YMPI.store.s_rekapjemputan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.rekapjemputanStore',
	model	: 'YMPI.model.m_rekapjemputan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'rekapjemputan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_rekapjemputan/getAll',
			create	: 'c_rekapjemputan/save',
			update	: 'c_rekapjemputan/save',
			destroy	: 'c_rekapjemputan/delete'
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