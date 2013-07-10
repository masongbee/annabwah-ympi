Ext.define('YMPI.view.TRANSAKSI.CICILAN', {
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
				xtype	: 'Listcicilan',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});