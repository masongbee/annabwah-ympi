Ext.define('YMPI.view.MASTER.KELOMPOK', {
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
				xtype	: 'Listkelompok',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});