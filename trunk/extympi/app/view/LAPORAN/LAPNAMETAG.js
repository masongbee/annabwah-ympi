Ext.define('YMPI.view.LAPORAN.LAPNAMETAG', {
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
				xtype	: 'Listlapnametag',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});