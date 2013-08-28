Ext.define('YMPI.store.s_trmakan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.trmakanStore',
	model	: 'YMPI.model.m_trmakan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'trmakan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_trmakan/getAll',
			create	: 'c_trmakan/save',
			update	: 'c_trmakan/save',
			destroy	: 'c_trmakan/delete'
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