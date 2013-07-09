Ext.define('YMPI.store.s_kalenderlibur', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.kalenderliburStore',
	model	: 'YMPI.model.m_kalenderlibur',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'kalenderlibur',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_kalenderlibur/getAll',
			create	: 'c_kalenderlibur/save',
			update	: 'c_kalenderlibur/save',
			destroy	: 'c_kalenderlibur/delete'
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