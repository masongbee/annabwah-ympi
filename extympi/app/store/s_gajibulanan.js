Ext.define('YMPI.store.s_gajibulanan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.gajibulananStore',
	model	: 'YMPI.model.m_gajibulanan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'gajibulanan',
	
	pageSize	: 500, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_gajibulanan/getAll',
			create	: 'c_gajibulanan/save',
			update	: 'c_gajibulanan/save',
			destroy	: 'c_gajibulanan/delete'
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