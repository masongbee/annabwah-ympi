Ext.define('YMPI.view.TRANSAKSI.POTONGANSP', {
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
				xtype	: 'Listpotongansp',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});