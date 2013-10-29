Ext.define('YMPI.store.s_shiftjamkerja', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.shiftjamkerjaStore',
	model	: 'YMPI.model.m_shiftjamkerja',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: 'shiftjamkerja',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_shiftjamkerja/getAll',
			create	: 'c_shiftjamkerja/save',
			update	: 'c_shiftjamkerja/save',
			destroy	: 'c_shiftjamkerja/delete'
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