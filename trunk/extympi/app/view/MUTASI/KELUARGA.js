Ext.define('YMPI.view.MUTASI.KELUARGA', {
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
				xtype	: 'Listkeluarga',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});