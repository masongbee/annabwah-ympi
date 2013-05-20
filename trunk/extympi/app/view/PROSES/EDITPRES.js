Ext.define('YMPI.view.PROSES.EDITPRES', {
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
				xtype	: 'Listeditpres',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});