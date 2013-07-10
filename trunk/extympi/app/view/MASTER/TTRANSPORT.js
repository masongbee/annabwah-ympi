Ext.define('YMPI.view.MASTER.TTRANSPORT', {
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
				xtype	: 'Listttransport',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});