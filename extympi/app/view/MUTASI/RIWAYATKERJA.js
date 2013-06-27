Ext.define('YMPI.view.MUTASI.RIWAYATKERJA', {
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
				xtype	: 'Listriwayatkerja',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});