Ext.define('YMPI.store.s_potongansp', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.potonganspStore',
	model	: 'YMPI.model.m_potongansp',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'potongansp',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_potongansp/getAll',
			create	: 'c_potongansp/save',
			update	: 'c_potongansp/save',
			destroy	: 'c_potongansp/delete'
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