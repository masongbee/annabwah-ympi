Ext.define('YMPI.store.s_editpres', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.editpresStore',
	model	: 'YMPI.model.m_editpres',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'editpres',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_editpres/getAll',
			create	: 'c_editpres/save',
			update	: 'c_editpres/save',
			destroy	: 'c_editpres/delete'
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