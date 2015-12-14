Ext.define('YMPI.view.TRANSAKSI.HSLSELEKSI', {
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
				xtype	: 'Listhslseleksi',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});