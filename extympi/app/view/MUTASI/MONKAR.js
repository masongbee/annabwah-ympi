Ext.define('YMPI.view.MUTASI.MONKAR', {
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
				xtype	: 'Listmonkar',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});