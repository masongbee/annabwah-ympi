Ext.define('YMPI.view.MUTASI.RIWAYATSEHAT', {
	extend: 'Ext.form.Panel',
	
	bodyPadding: 0,
	layout: 'border',
	initComponent: function(){
		this.items = [{
			region: 'center',
			layout: {
				type : 'hbox',
				align: 'stretch'
			},
			items: [{
				xtype	: 'Listriwayatsehat',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});