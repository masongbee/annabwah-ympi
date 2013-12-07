Ext.define('YMPI.store.s_pjamsostek', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.pjamsostekStore',
	model	: 'YMPI.model.m_pjamsostek',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'pjamsostek',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_pjamsostek/getAll',
			create	: 'c_pjamsostek/save',
			update	: 'c_pjamsostek/save',
			destroy	: 'c_pjamsostek/delete'
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