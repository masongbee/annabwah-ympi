Ext.define('YMPI.view.MASTER.UNITKERJA', {
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
				xtype	: 'Listunitkerja',
				flex: 1
			}, {
				xtype	: 'Listjabatan',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});