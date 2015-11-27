Ext.define('YMPI.store.s_lappenugasankar', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.td_pelatihanStore',
	model	: 'YMPI.model.m_penugasankar',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'lappenugasankar',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_penugasankar/lappenugasankar'
		},
		actionMethods: {
			read    : 'POST'
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