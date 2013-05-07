Ext.define('YMPI.view.MASTER.GRADE', {
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
				xtype	: 'Listgrade',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});