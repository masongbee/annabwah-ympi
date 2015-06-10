Ext.define('YMPI.store.s_td_pelatihan', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.td_pelatihanStore',
	model	: 'YMPI.model.m_td_pelatihan',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'td_pelatihan',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_td_pelatihan/getAll',
			create	: 'c_td_pelatihan/save',
			update	: 'c_td_pelatihan/save',
			destroy	: 'c_td_pelatihan/delete'
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