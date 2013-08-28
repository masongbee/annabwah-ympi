Ext.define('YMPI.store.s_tkacamata', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.tkacamataStore',
	model	: 'YMPI.model.m_tkacamata',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: 'tkacamata',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_tkacamata/getAll',
			create	: 'c_tkacamata/save',
			update	: 'c_tkacamata/save',
			destroy	: 'c_tkacamata/delete'
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