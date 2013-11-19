Ext.define('YMPI.view.TRANSAKSI.RINCIANCUTI', {
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
				xtype	: 'Listrinciancuti',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});