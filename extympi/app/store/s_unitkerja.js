Ext.define('YMPI.store.s_unitkerja', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.unitkerjaStore',
	model	: 'YMPI.model.m_unitkerja',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'unitkerja',
	
	pageSize	: 100, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_unitkerja/getAll',
			create	: 'c_unitkerja/save',
			update	: 'c_unitkerja/save',
			destroy	: 'c_unitkerja/delete'
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