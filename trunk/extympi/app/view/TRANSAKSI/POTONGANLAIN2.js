Ext.define('YMPI.view.TRANSAKSI.POTONGANLAIN2', {
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
				xtype	: 'Listpotonganlain2',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});