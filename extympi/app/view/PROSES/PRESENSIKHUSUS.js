Ext.define('YMPI.view.PROSES.PRESENSIKHUSUS', {
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
				xtype	: 'Listpresensikhusus',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});