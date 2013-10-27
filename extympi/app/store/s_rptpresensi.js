Ext.define('YMPI.store.s_rptpresensi', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.rptpresensiStore',
	model	: 'YMPI.model.m_rptpresensi',
	
	autoLoad	: true,
	autoSync	: false,
    remoteSort	: true,
	//remoteGroup	: true,
	simpleSortMode: true,
	storeId		: 'rptpresensi',
	
	pageSize	: 20, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_rptpresensi/getAll'
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
				//var ms = Ext.JSON.decode(operation.getError());
				//console.info(operation.getError());
				Ext.MessageBox.show({
					title: 'REMOTE EXCEPTION',
					msg: operation.getError().statusText,
					icon: Ext.MessageBox.ERROR,
					buttons: Ext.Msg.OK
				});
			}
		}
	},
	
    groupField: 'SHIFTKE',
	
	constructor: function(){
		this.callParent(arguments);
	}
	
});