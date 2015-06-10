Ext.define('YMPI.view.MASTER.TD_TRAINER', {
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
				xtype	: 'Listtd_trainer',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});