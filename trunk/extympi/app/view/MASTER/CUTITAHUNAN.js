Ext.define('YMPI.view.MASTER.CUTITAHUNAN', {
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
				xtype	: 'Listcutitahunan',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});